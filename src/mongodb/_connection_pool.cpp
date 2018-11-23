#include "../controller.h"
#include "_connection_pool.h"

namespace flame::mongodb 
{
    _connection_pool::_connection_pool(const std::string& url)
    {
        std::unique_ptr<mongoc_uri_t, void (*)(mongoc_uri_t *)> uri(mongoc_uri_new(url.c_str()), mongoc_uri_destroy);
        const bson_t* options = mongoc_uri_get_options(uri.get());
        if (!bson_has_field(options, MONGOC_URI_READPREFERENCE))
        {
            mongoc_uri_set_option_as_utf8(uri.get(), MONGOC_URI_READPREFERENCE, "secondaryPreferred");
        }
        mongoc_uri_set_option_as_int32(uri.get(), MONGOC_URI_CONNECTTIMEOUTMS, 5000);
        mongoc_uri_set_option_as_int32(uri.get(), MONGOC_URI_MAXPOOLSIZE, 16);

        p_ = mongoc_client_pool_new(uri.get());
    }
    _connection_pool::~_connection_pool() {
        mongoc_client_pool_destroy(p_);
    }
    std::shared_ptr<mongoc_client_t> _connection_pool::acquire(coroutine_handler &ch)
    {
        std::shared_ptr<mongoc_client_t> conn;
        auto self = shared_from_this();
        boost::asio::post(gcontroller->context_y, [this, self, &conn, &ch] () {
            // 对应释放(归还)连接过程, 须持有当前对象的引用 self 
            // (否则当前对象可能先于连接释放被销毁)
            conn.reset(mongoc_client_pool_pop(p_), [this, self] (mongoc_client_t* c)
            {
                mongoc_client_pool_push(p_, c);
            });
            boost::asio::post(gcontroller->context_x, std::bind(&coroutine_handler::resume, ch));
        });
        ch.suspend();
        return conn;
    }
}