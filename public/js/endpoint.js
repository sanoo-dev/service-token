/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************!*\
  !*** ./resources/js/endpoint.js ***!
  \**********************************/
// document.addEventListener("DOMContentLoaded", function() {
//     function textToSlug(text) {
//         // Remove diacritics from Vietnamese characters
//         const slug = text
//             .normalize("NFD")
//             .replace(/[\u0300-\u036f]/g, "");
//
//         // Replace special characters and spaces with hyphens
//         const normalizedSlug = slug
//             .toLowerCase()
//             .replace(/[^\w\s-]/g, "")
//             .replace(/\s+/g, "-");
//
//         return normalizedSlug;
//     }
//
//     function parseJwt (token) {
//         var base64Url = token.split('.')[1];
//         var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
//         var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
//             return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
//         }).join(''));
//
//         return JSON.parse(jsonPayload);
//     }
//
//
//     function getCookie(cname) {
//         let name = cname + "=";
//         let decodedCookie = decodeURIComponent(document.cookie);
//         let ca = decodedCookie.split(';');
//         for(let i = 0; i <ca.length; i++) {
//             let c = ca[i];
//             while (c.charAt(0) == ' ') {
//                 c = c.substring(1);
//             }
//             if (c.indexOf(name) == 0) {
//                 return c.substring(name.length, c.length);
//             }
//         }
//         return "";
//     }
//
//     function deleteCookie(name, domain) {
//         document.cookie = name + '=; expires=Thu, 01 Jan 2020 00:00:00 UTC; domain=' + domain + '; path=/;';
//     }
//
//     window.onload = function () {
//         let login_erpLOGIN_ERP
//         let auth = getCookie('_ttoauth_prod');
//
//         if (auth.length == 0) {
//             window.location.href = '${{
//             window.location.href = '{{env('URL_ERP_LOGIN').env('URL_TOKEN')}}';
//         } else {
//             let ac = parseJwt(auth)['access_token'];
//             console.log(auth)
//             var slug_department_name = ''; // check call api get slug
//             fetch('http://192.168.61.116:8012/users/users/me/', {
//                 method: 'GET',
//                 type: "GET",
//                 headers: {
//                     'Content-Type': 'application/json',
//                     "Authorization": `Bearer ${ac}`
//                 },
//             })
//
//                 .then(response => response.json())
//                 .then(data => {
//                     if (data['data']['user_dep_pos'][0]['parent']['name'].length > 0) {
//                         let department_name = data['data']['user_dep_pos'][0]['parent']['name'];
//                         let email = data['data']['email'];
//
//                         slug_department_name = textToSlug(department_name);
//
//                         if (slug_department_name.length > 0) {
//                             const apiUrl = '{{env('APP_URL')}}/api/auth/check-account';
//                             const temp = {slug: slug_department_name, email: email};
//
//                             // Convert the data object to URL-encoded format
//                             const params = new URLSearchParams(temp);
//                             const urlWithParams = apiUrl + '?' + params.toString();
//                             fetch(urlWithParams, {
//                                 method: 'GET',
//                                 headers: {
//                                     'Content-Type': 'application/json'
//                                 }
//
//                             })
//                                 .then(response => response.json())
//                                 .then(data => {
//                                     if (data['data'] === 'no') {
//
//                                         alert('Bạn không có quyền truy cập Service Token!');
//                                         deleteCookie('_ttoauth_prod', '.tuoitre.vn');
//                                         window.location.href = '{{env('URL_ERP_LOGIN').env('URL_TOKEN')}}';
//                                     } else {
//                                         window.location.href = '{{env('URL_TOKEN')}}';
//                                         window.stop()
//                                     }
//
//                                     {{--// Xử lý dữ liệu từ phản hồi của API ở đây--}}
//                                     })
//                                     .catch(error => {
//                                         alert('Bạn không có quyền truy cập Service Token!');
//                                         deleteCookie('_ttoauth_prod', '.tuoitre.vn');
//                                         window.location.href = '{{env('URL_ERP_LOGIN').env('URL_TOKEN')}}';
//                                     });
//
//
// // console.log(slug_department_name, checkAssign(slug_department_name));e;
//                                         // if (checkAssign(slug_department_name)) {
//                                         //     alert('ok');
//                                         // } else {
//                                         //     alert('notttt');
//                                         // }
//
//                                     }
//                                 }
//                         })
//                     .catch(error => {
//                             alert('Bạn không có quyền truy cập Service Token!');
//                             deleteCookie('_ttoauth_prod', '.tuoitre.vn');
//                             window.location.href = '{{env('URL_ERP_LOGIN').env('URL_TOKEN')}}';
//                         });
//                     }
//                 }
//         }
//     }
//
// });
/******/ })()
;