<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Helpers\Constants\ConstantDefine;
use App\Modules\Auth\Helpers\ResponseHelper;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\AccountRepository;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\PermissionRepositoryInterface;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\RoleRepositoryInterface;
use Common\App\Models\Account;
use Common\App\Models\Permission;
use Common\App\Models\Role;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class AuthService implements \App\Modules\Auth\Services\Interfaces\AuthService
{
    public function __construct(
        protected ResponseHelper                $responseHelper,
        protected AccountRepository             $accountRepository,
        protected RoleRepositoryInterface       $roleRepository,
        protected PermissionRepositoryInterface $permissionRepository,
        protected CookieFactory                 $cookie,

    )
    {
        $this->redis = Redis::connection('route')->client();
        $this->redis_account = Redis::connection('account')->client();
    }

    public function responseReturn($code, $mess, $data = [])
    {
        $mess = [
            'code' => $code,
            'message' => $mess,
            'data' => $data

        ];
        return $mess;
    }


    public function createAccount($data)
    {
        $email = !empty($data['email']) ? $data['email'] : null;
        $password = !empty($data['password']) ? $data['password'] : null;
        $id_role = !empty($data['id_role']) ? $data['id_role'] : 1;
        $name_role = !empty($data['name_role']) ? $data['name_role'] : 1;
        //check account
        $check = $this->accountRepository->findByAttributes(['email' => $email]);
        // neu account khong ton  tai hoac khong cos phan quyen
        if (empty($check) || empty($check['_source']['id_role'])) {
            $data = [
                'guid' => \Str::uuid()->getHex()->toString(),
                'email' => $email,
                'id_role' => json_encode([$id_role]),
                'name_role' => json_encode([$name_role]),
                'password_hash' => Hash::make($password),
                'created_at' => time(),
                'updated_at' => time(),
            ];

            $saveDB = Account::query()->create($data);


            usleep(1000);
            $id = $saveDB->getAttribute('id');
            $data['db_id'] = $id;
            $saveES = $this->accountRepository->updateOrCreate($data);

            if ($saveES && $saveDB) {
                $return = [
                    'username' => $data['guid'],
                    'password' => Hash::make($password)
                ];

                $this->redis_account->set('account::' . $data['guid'], $data['id_role']);

                $mess = $this->responseReturn(ConstantDefine::NO_ERROR, 'Tài khoản tạo  thành công', $return);
                return $mess;
            } else {

                $mess = $this->responseReturn(ConstantDefine::ERROR_ALL, 'Tài khoản tạo không thành công', []);
                return $mess;

            }
        } else {
            $id_save = null;
            $name_role = null;
            foreach ($id_role as $key => $id) {
                $search = $this->roleRepository->findByAttributes(['db_id' => $id]);

                if (!empty($search)) {
                    $id_save[] = $search['_source']['db_id'];
                    $name_role[$key] = $search['_source']['name'] ?? null;
                }

            }
          if (!empty($id_save)){
              $data = [
                  'id_role' => json_encode($id_save),
                  'name_role' => json_encode($name_role),

              ];

              $saveDB = Account::query()->find($check['_source']['db_id'])->update($data);

              usleep(1000);
              $data['guid'] = $check['_source']['guid'];
              $data['updated_at'] = time();
              $saveES = $this->accountRepository->update($data);

              if ($saveES && $saveDB) {
                  $return = [
                      'guid' => $data['guid'],
                      'id' => $data['id_role']
                  ];

                  $this->redis_account->set('account::' . $data['guid'], $data['id_role']);

                  $mess = $this->responseReturn(ConstantDefine::EMPTY, 'Tài khoản đã tồn tại thêm quyền thành công', $return);
                  return $mess;
              }
              $mess = $this->responseReturn(ConstantDefine::EMPTY, 'Tài khoản tạo không thành công', []);
              return $mess;
          }else{
              $mess = $this->responseReturn(ConstantDefine::EMPTY, 'Tài khoản tạo không thành công không có role ', []);
              return $mess;
          }
        }


    }


    public function crearteRole($data)
    {
        $name = !empty($data['name']) ? $data['name'] : null;
        $id_permission = !empty($data['id_permission']) ? $data['id_permission'] : null;
        $name_permission = !empty($data['name_action']) ? $data['name_action'] : null;
        $status = !empty($data['status']) ? $data['status'] : null;

        $check = $this->roleRepository->findByAttributes(['name' => $name]);

        if (empty($check)) {
            $id_save=[];
            $name_save=[];
            $route=[];
            foreach ($id_permission as $key=>$id){
                $check_permission = $this->permissionRepository->findByAttributes(['db_id' => $id]);
                if (!empty($check_permission)){
                    $id_save[]=$id;
                    $name_save[]=$check_permission['_source']['action'];
                    $route[]=$check_permission['_source']['route'];
                }
            }

            $data = [
                'name' => $name,
                'id_permission' => json_encode($id_save),
                'name_permission' => json_encode($name_save),
                'status' => $status,
                'created_at' => time(),
                'updated_at' => time(),
            ];

            $saveDB = Role::query()->create($data);

            usleep(1000);
            $id = $saveDB->getAttribute('id');
            $data['db_id'] = $id;
            $saveES = $this->roleRepository->create($data);

            if ($saveES && $saveDB) {
                $return = [
                    'name' => $name,
                ];
                // luu cache redis
                $data_redis = $name;

                $this->redis->set('role::' . $id, json_encode($route));
                $mess = $this->responseReturn(ConstantDefine::NO_ERROR, 'Tạo  thành công', $return);
                return $mess;
            } else {

                $mess = $this->responseReturn(ConstantDefine::ERROR_ALL, 'Tạo không thành công', []);
                return $mess;

            }
        } else {

//            $temp_form_id[] = json_decode($id_permission);
            $temp_form_id = [];
            $temp_name=[];
            $route=[];

            foreach ($id_permission as $ke => $list_permisson) {
                        $check_permission = $this->permissionRepository->findByAttributes(['db_id' => $list_permisson]);
                        if (!empty($check_permission)){
                            $temp_form_id[] = $check_permission['_source']['db_id'];
                            $temp_name[] = $check_permission['_source']['action'];
                            $route[]=$check_permission['_source']['route'];
                        }
            }

            $data = [
                'id_permission' => json_encode($temp_form_id),
                'name_permission' => json_encode($temp_name),
                'status' => $status,
                'updated_at' => time(),
            ];

            $saveDB = Role::query()->find($check['_source']['db_id'])->update($data);
            $this->redis->set('role::' . $check['_source']['db_id'], json_encode($route));

            $data['db_id'] = $check['_source']['db_id'];
            usleep(1000);

            $saveES = $this->roleRepository->update($data);
            $mess = $this->responseReturn(ConstantDefine::EMPTY, 'tồn tại them route cho quyen thanh cong', $data);
            return $mess;

        }
    }

    public function createPermission($data)
    {
        $action = !empty($data['action']) ? $data['action'] : null;
        $route = !empty($data['route']) ? $data['route'] : null;
        $status = !empty($data['status']) ? $data['status'] : null;

        $check = $this->permissionRepository->findByAttributes(['action' => $action]);
        if (empty($check)) {
            $data = [
                'action' => $action,
                'route' => $route,
                'status' => $status,
                'created_at' => time(),
                'updated_at' => time(),
            ];
            $saveDB = Permission::query()->create($data);

            usleep(1000);
            $id = $saveDB->getAttribute('id');
            $data['db_id'] = $id;
            $saveES = $this->permissionRepository->create($data);

            if ($saveES && $saveDB) {
                $return = [
                    'action' => $action,
                ];

                $mess = $this->responseReturn(ConstantDefine::NO_ERROR, 'Tạo  thành công', $return);
                return $mess;
            } else {

                $mess = $this->responseReturn(ConstantDefine::ERROR_ALL, 'Tạo không thành công', []);
                return $mess;

            }
        } else {

            $mess = $this->responseReturn(ConstantDefine::EMPTY, 'tồn tại', []);
            return $mess;

        }
    }
}
