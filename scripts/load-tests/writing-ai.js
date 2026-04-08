import http from 'k6/http';
import { check, sleep } from 'k6';

const baseUrl = __ENV.BASE_URL || 'https://simple-eng.io';
const sessionCookie = __ENV.SESSION_COOKIE || '';
const writingExerciseId = __ENV.WRITING_EXERCISE_ID || '';

if (!sessionCookie || !writingExerciseId) {
    throw new Error('SESSION_COOKIE and WRITING_EXERCISE_ID are required.');
}

export const options = {
    scenarios: {
        writing_ai: {
            executor: 'ramping-vus',
            startVUs: 1,
            stages: [
                { duration: '30s', target: 10 },
                { duration: '1m', target: 30 },
                { duration: '1m', target: 50 },
                { duration: '30s', target: 0 },
            ],
            gracefulRampDown: '20s',
        },
    },
    thresholds: {
        http_req_failed: ['rate<0.05'],
        http_req_duration: ['p(95)<120000'],
    },
};

function buildHeaders() {
    return {
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
        'Cookie': `laravel_session=${sessionCookie}`,
    };
}

function buildAnswer() {
    return [
        'I study English every day because I want to speak clearly and write better sentences.',
        'In this lesson I learned new words, and I try to use them in my writing naturally.',
        'I review grammar carefully, and I check punctuation before I submit my answer.',
        'Practice helps me remember vocabulary and become more confident when I communicate.',
    ].join(' ');
}

export default function () {
    const response = http.post(
        `${baseUrl}/student/writing/${writingExerciseId}/submit`,
        {
            answer_text: buildAnswer(),
        },
        {
            headers: buildHeaders(),
            timeout: '130s',
        }
    );

    check(response, {
        'writing status is 200': (r) => r.status === 200,
        'writing success true': (r) => {
            try {
                return JSON.parse(r.body).success === true;
            } catch (error) {
                return false;
            }
        },
    });

    sleep(1);
}
