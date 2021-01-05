#include "time.h"
#include "../clock.h"

namespace core { namespace extension {

    void time::declare(php::module_entry& entry) {
        entry 
            - php::function<time::sleep>("flame\\time\\sleep", {
                {"milliseconds", php::TYPE_INTEGER},
            })
            - php::function<time::now>("flame\\time\\now")
            - php::function<time::iso>("flame\\time\\iso")
            - php::function<time::utc>("flame\\time\\utc");
    }
    // 获取当前时间戳（毫秒）
    php::value time::now(php::parameters& params) {
        return static_cast<std::int64_t>(clock::get_const_instance());
    }
    // 当前时间串
    php::value time::iso(php::parameters& params) {
        return clock::get_const_instance().iso();
    }
    // 当前时间串
    php::value time::utc(php::parameters& params) {
        return clock::get_const_instance().utc();
    }
    // 暂定当前协程，并在若干时间后恢复
    php::value time::sleep(php::parameters& params) {
        // TODO
        return nullptr;
    }

}}