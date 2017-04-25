ROOT_TERRYWH=/data/wuhao/cpdocs/github.com/terrywh

EXTENSION_NAME=mill
EXTENSION_VERSION=0.1.0

ROOT_PROJECT=${ROOT_TERRYWH}/php-${EXTENSION_NAME}

VENDOR_LIBRARY=${ROOT_TERRYWH}/libphpext/libphpext.a ${ROOT_PROJECT}/vendor/libmill/.libs/libmill.a

PHP=php
PHP_CONFIG=php-config

CXX?=g++
CXXFLAGS?= -g -O0
INCLUDE=-I${ROOT_TERRYWH}/libphpext -I${ROOT_PROJECT}/vendor/libmill `${PHP_CONFIG} --includes`
LIBRARY=${VENDOR_LIBRARY}

SOURCES=$(wildcard src/*.cpp) $(wildcard src/**/*.cpp) $(wildcard src/**/**/*.cpp)
OBJECTS=$(SOURCES:%.cpp=%.o)

EXTENSION=${EXTENSION_NAME}.so

.PHONY: install clean

# 暂时先将 libphpext.a 作为依赖（库还不稳定）
${EXTENSION}: ${OBJECTS} ${ROOT_TERRYWH}/libphpext/libphpext.a
	${CXX} -shared ${OBJECTS} ${LIBRARY} -Wl,-rpath=/usr/local/gcc6/lib64 -o ${EXTENSION_NAME}.so
%.o: %.cpp
	${CXX} -std=c++11 -fPIC -DMILL_USE_PREFIX -DEXTENSION_NAME=\"${EXTENSION_NAME}\" -DEXTENSION_VERSION=\"${EXTENSION_VERSION}\" ${CXXFLAGS} ${INCLUDE} -c $^ -o $@

clean:
	rm -f ${EXTENSION} ${OBJECTS}
install: ${EXTENSION}
	cp -f ${EXTENSION} `${PHP_CONFIG} --extension-dir`

test:
	${PHP} udp/server.php
