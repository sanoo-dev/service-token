#FROM bitnami/elasticsearch:7.12.1
FROM elasticsearch:7.17.4

MAINTAINER NguyenQuocDai <quocdaijr@gmail.com>

RUN chmod -R 1000 /usr/share/elasticsearch/data

ADD ./config/elasticsearch.yml /usr/share/elasticsearch/config/elasticsearch.yml

# Use icu tokenizer offline

ADD ./plugins/analysis-icu-7.17.4.zip /tmp/analysis-icu-7.17.4.zip
RUN bin/elasticsearch-plugin install file:///tmp/analysis-icu-7.17.4.zip

# Use elasticsearch-analysis-vietnamese online
# link coccoc-tokenizer: https://github.com/coccoc/coccoc-tokenizer
# linkelasticsearch-analysis-vietnamese: https://github.com/duydo/elasticsearch-analysis-vietnamese

#USER 0

#RUN apt-get update && apt-get -y install build-essential libssl-dev wget git
#
#RUN wget https://cmake.org/files/v3.18/cmake-3.18.0.tar.gz && \
#    tar -xvzf cmake-3.18.0.tar.gz && \
#    cd cmake-3.18.0 && \
#    ./bootstrap && \
#    make && \
#    make install
#
#RUN apt-get update && apt-get -y install openjdk-11-jdk maven
#
#RUN git clone https://github.com/coccoc/coccoc-tokenizer.git && \
#    cd coccoc-tokenizer && \
#    mkdir build && cd build && \
#    cmake -DBUILD_JAVA=1 .. && make install
#
#RUN git clone https://github.com/duydo/elasticsearch-analysis-vietnamese.git
#COPY ./config/pom.xml /elasticsearch-analysis-vietnamese/
#RUN cd /elasticsearch-analysis-vietnamese/ && cat pom.xml && mvn -U package
#RUN /opt/bitnami/elasticsearch/bin/elasticsearch-plugin install analysis-icu
#RUN /opt/bitnami/elasticsearch/bin/elasticsearch-plugin install --batch file:///elasticsearch-analysis-vietnamese/target/releases/elasticsearch-analysis-vietnamese-7.12.1.zip
#
#RUN cp coccoc-tokenizer/build/libcoccoc_tokenizer_jni.so /usr/lib/


ENV ES_JAVA_OPTS="-Xms512m -Xmx512m"
ENV MAX_OPEN_FILES=262144
ENV MAX_LOCKED_MEMORY=unlimited

RUN echo "* - nofile 262144" >> /etc/security/limits.conf && \
    echo "* - memlock 262144" >> /etc/security/limits.conf && \
    echo vm.max_map_count=262144 >> /etc/sysctl.d/99-max-map-count.conf && \
    echo vm.swappiness=0 > /etc/sysctl.d/swappiness.conf

RUN apt-get update && \
    apt-get install -yq tzdata && \
    ln -fs /usr/share/zoneinfo/Asia/Ho_Chi_Minh /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata

EXPOSE 9200 9300
