#pragma once
#include "client_handler.h"

namespace flame {
namespace net {
	class unix_socket: public php::class_base {
	public:
		typedef client_handler<uv_pipe_t, unix_socket> handler_t;
		unix_socket();
		~unix_socket();
		php::value connect(php::parameters& params);
		inline php::value read(php::parameters& params) {
			return handler->read(params);
		}
		inline php::value write(php::parameters& params) {
			return handler->write(params);
		}
		inline php::value close(php::parameters& params) {
			return handler->close(params);
		}
		// property remote_address ""
		handler_t* handler; // 由于异步关闭需要等待 close_cb 才能进行回收

		void after_init();
	private:
		static void connect_cb(uv_connect_t* req, int status);
		friend class unix_server;
	};
}
}
