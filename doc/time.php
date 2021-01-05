<?php
/**
 * 时间相关函数
 */
namespace flame\time;
/**
 * 获取毫秒时间戳
 * 注意：
 *  1. 此时间戳使用相对比直接获取时钟消耗更小的方式计算系统时间；
 */
function now():int {
    return 1557659996427;
}
/**
 * 获取当前时区标准时间 "YYYY-mm-dd HH:ii:ss"
 */
function iso():string {
    return "2019-03-31 21:40:25";
}
/**
 * 获取UTC标准时间 "YYYY-mm-ddTHH:ii:ssZ"
 */
function utc():string {
    return "2020-01-15T17:30:24Z";
}
/**
 * 暂停、挂起当前协程（调度执行其他活跃协程），并在 $ms 毫秒后恢复
 * 注意：
 *  1. 使用 PHP 内置的 `sleep()`/`usleep()` 函数会阻塞协程调度；
 */
function sleep(int $ms) {}
/**
 * 优先级队列实现的定时器集合
 * 一般用于处理需要较大量定时器的场景
 */
class priority_timer_set {
    /**
     * 
     */
    function add(int $tick, callable $cb):int {
        return 0xffffffffff123456;
    }
};