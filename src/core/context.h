#ifndef CPP_CORE_CONTROLLER_H
#define CPP_CORE_CONTROLLER_H

#include "coroutine.h"
#include "clock.h"
#include "logger.h"

#include <boost/core/null_deleter.hpp>
#include <boost/asio/io_context.hpp>
#include <boost/asio/steady_timer.hpp>
#include <boost/process/environment.hpp>
#include <memory>
#include <random>
#include <string>

namespace core {

    class context;
    static std::unique_ptr<context> $context;
    // 工作上下文
    class context {
    public:
        enum status_t {
            STATUS_UNKNOWN  = 0,
            STATUS_INIT  = 0x01,
            STATUS_STOP  = 0x02,
            STATUS_ERROR = 0x04,
        };
        struct environment_t {
            pid_t  ppid; // 父进程 ID
            pid_t   pid; // 进程 ID
            int  status; // 整体状态
        } env;
        struct option_t {
            // 服务配置
            struct service_t {
                std::string name; // 服务名称
            } service;
            // 日志输出
            struct logger_t {
                core::log::logger::severity_t severity; // below which log record will be discarded
                std::vector<std::string>        target;
            } logger;
        } opt;
        // 随机引擎
        std::default_random_engine random;
        // 默认上下文（主线程，导出提供其他扩展使用）
        boost::asio::io_context io_m;
        // 支持上下文（辅线程，导出提供其他扩展使用）
        boost::asio::io_context io_w;
    public:
        // 
        context();

        bool in_status(status_t s);
        // 协程辅助
        template <typename CoroutineHandlerT>
        void co_sleep(std::chrono::milliseconds ms, CoroutineHandlerT&& ch, boost::asio::io_context& io = $context->io_m) {
#ifndef NDEBUG
            assert(io.get_executor() == ch.executor());
#endif
            boost::asio::steady_timer tm { io };
            tm.expires_after(ms);
            tm.async_wait(ch);
        }
        // 事件辅助
        template <typename Handler>
        void ev_after(std::chrono::milliseconds ms, Handler&& cb, boost::asio::io_context& io = $context->io_m) {
            auto tm = std::make_unique<boost::asio::steady_timer>(io);
            tm->expires_after(ms);
            tm->async_wait([tm = std::move(tm), cb = std::move(cb)] (const boost::system::error_code& error) {
                cb();
            });
        }
    };

}

#endif // CPP_CORE_CONTROLLER_H
