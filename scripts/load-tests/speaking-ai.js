import http from 'k6/http';
import { check, sleep } from 'k6';

const baseUrl = __ENV.BASE_URL || 'https://simple-eng.io';
const sessionCookie = __ENV.SESSION_COOKIE || '';
const pronunciationExerciseId = __ENV.PRONUNCIATION_EXERCISE_ID || '';
const sentenceNumber = __ENV.SENTENCE_NUMBER || '1';
const audioFilePath = __ENV.AUDIO_FILE || './sample.webm';
const audioMimeType = __ENV.AUDIO_MIME_TYPE || 'audio/webm';
const audioBinary = open(audioFilePath, 'b');

if (!sessionCookie || !pronunciationExerciseId) {
    throw new Error('SESSION_COOKIE and PRONUNCIATION_EXERCISE_ID are required.');
}

export const options = {
    scenarios: {
        speaking_ai: {
            executor: 'ramping-vus',
            startVUs: 1,
            stages: [
                { duration: '30s', target: 5 },
                { duration: '1m', target: 15 },
                { duration: '1m', target: 25 },
                { duration: '30s', target: 0 },
            ],
            gracefulRampDown: '20s',
        },
    },
    thresholds: {
        http_req_failed: ['rate<0.08'],
        http_req_duration: ['p(95)<90000'],
    },
};

function buildHeaders() {
    return {
        'Accept': 'application/json',
        'Cookie': `laravel_session=${sessionCookie}`,
    };
}

function uploadOnce() {
    const payload = {
        exercise_id: String(pronunciationExerciseId),
        sentence_number: String(sentenceNumber),
        duration_seconds: '8',
        provider: 'media_upload',
        audio: http.file(audioBinary, 'sample.webm', audioMimeType),
    };

    return http.post(`${baseUrl}/student/pronunciation/upload`, payload, {
        headers: buildHeaders(),
        timeout: '90s',
    });
}

function pollStatus(token) {
    for (let i = 0; i < 40; i += 1) {
        const response = http.get(`${baseUrl}/student/pronunciation/status/${token}`, {
            headers: buildHeaders(),
            timeout: '45s',
        });

        if (response.status !== 200) {
            return response;
        }

        const body = response.json();
        if (body.status === 'completed' || body.status === 'failed') {
            return response;
        }

        sleep(2);
    }

    return null;
}

export default function () {
    const uploadResponse = uploadOnce();

    const uploadOk = check(uploadResponse, {
        'upload accepted': (r) => r.status === 202,
        'upload returned token': (r) => {
            try {
                return Boolean(JSON.parse(r.body).upload_token);
            } catch (error) {
                return false;
            }
        },
    });

    if (!uploadOk) {
        sleep(1);
        return;
    }

    const uploadBody = uploadResponse.json();
    const statusResponse = pollStatus(uploadBody.upload_token);

    check(statusResponse, {
        'status response exists': (r) => r !== null,
        'status endpoint returned 200': (r) => r && r.status === 200,
        'speaking completed': (r) => {
            if (!r) return false;
            const body = r.json();
            return body.status === 'completed' && body.success === true;
        },
    });

    sleep(1);
}
