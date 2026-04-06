#!/usr/bin/env python3
"""
Send one uploaded audio file to pronunciation gateway over websocket and return final transcript as JSON.
"""

from __future__ import annotations

import argparse
import asyncio
import base64
import json
import os
import sys
import uuid


async def run(ws_url: str, audio_file: str, mime_type: str, timeout_seconds: int) -> dict:
    try:
        import websockets  # type: ignore
    except Exception as exc:  # pragma: no cover
        return {"success": False, "error": f"websockets import failed: {exc}"}

    if not os.path.isfile(audio_file):
        return {"success": False, "error": "audio file not found"}

    with open(audio_file, "rb") as f:
        audio_bytes = f.read()

    if not audio_bytes:
        return {"success": False, "error": "audio file is empty"}

    session_id = str(uuid.uuid4())
    encoded = base64.b64encode(audio_bytes).decode("ascii")

    async with websockets.connect(ws_url, open_timeout=10, close_timeout=5, max_size=2**24) as ws:
        await ws.send(json.dumps({
            "type": "start",
            "session_id": session_id,
            "sentence_number": 1,
            "expected_text": "",
        }))

        await ws.send(json.dumps({
            "type": "audio_chunk",
            "session_id": session_id,
            "sentence_number": 1,
            "mime_type": mime_type or "audio/webm",
            "chunk_ms": 1000,
            "audio_base64": encoded,
            "client_ts": int(asyncio.get_running_loop().time() * 1000),
        }))

        await ws.send(json.dumps({
            "type": "stop",
            "session_id": session_id,
        }))

        async def wait_for_final() -> dict:
            partial = ""
            while True:
                raw = await ws.recv()
                payload = json.loads(raw)
                partial = str(payload.get("partial_transcript") or payload.get("transcript") or partial).strip()
                if payload.get("transcript") is not None:
                    return {"success": True, "transcript": str(payload.get("transcript") or "").strip() or partial}

        try:
            return await asyncio.wait_for(wait_for_final(), timeout=max(10, timeout_seconds))
        except asyncio.TimeoutError:
            return {"success": False, "error": "timeout waiting for final transcript"}


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--ws-url", required=True)
    parser.add_argument("--audio-file", required=True)
    parser.add_argument("--mime-type", default="audio/webm")
    parser.add_argument("--timeout-seconds", type=int, default=90)
    args = parser.parse_args()

    try:
        result = asyncio.run(run(args.ws_url, args.audio_file, args.mime_type, args.timeout_seconds))
    except Exception as exc:  # pragma: no cover
        result = {"success": False, "error": str(exc)}

    print(json.dumps(result, ensure_ascii=False))
    return 0 if result.get("success") else 1


if __name__ == "__main__":
    raise SystemExit(main())

