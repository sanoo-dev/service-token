FROM ubuntu:20.04

# Cài đặt Memcached và các gói phụ trợ
RUN apt-get update && \
    apt-get install -y memcached libmemcached-dev libmemcached-tools

# Chạy Memcached
CMD ["memcached", "-u", "memcache", "-vv"]
