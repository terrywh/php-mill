#pragma once
#include "../vendor.h"
#include "_connection_base.h"

namespace flame::mongodb
{
    class _connection_lock : public _connection_base, public std::enable_shared_from_this<_connection_lock>
    {
    public:
        _connection_lock(std::shared_ptr<mongoc_client_t> c);
        std::shared_ptr<mongoc_client_t> acquire(coroutine_handler &ch) override;
        php::array fetch(std::shared_ptr<mongoc_cursor_t> cs, coroutine_handler &ch);
    private:
        std::shared_ptr<mongoc_client_t> conn_;
    };
} // namespace flame::mongodb
