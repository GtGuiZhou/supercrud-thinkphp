<?php
declare (strict_types = 1);

namespace app\middleware;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use think\facade\Db;
use UAParser\Parser;

class ApiAccessRecord
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //
        $begin = microtime(true);
        $res = $next($request);
        $end = microtime(true);
        try {
            $parser  = Parser::create();
            $result = $parser->parse($request->header('user-agent'));
            Db::table('api_access_record')->insert([
                'api' => $request->method().'-'.$request->rule()->getRule(),
                'ip' => $request->ip(),
                'os' => $result->os->family,
                'ua' => $result->ua->family,
                'device' => $result->device->family,
                'crawler' => (new CrawlerDetect())->isCrawler()?'yes':'no',
                'time' => round(($end - $begin) * 1000,2), // 毫秒
                'week' => date('w'),
                'hour' => date('H')
            ]);
        } finally {
            return  $res;
        }
    }
}
