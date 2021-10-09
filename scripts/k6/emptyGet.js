import http from 'k6/http';

import { check, sleep } from 'k6';

export default function () {
    let res = http.get('https://thebreaker-laravel-blog.herokuapp.com');

    check(res, { 'status was 200': (r) => r.status == 200 });
}