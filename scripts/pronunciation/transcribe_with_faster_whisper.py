#!/usr/bin/env python3
"""
Transcribe one uploaded audio file locally with faster-whisper and return JSON.
"""

from __future__ import annotations

import argparse
import json
import os


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--audio-file", required=True)
    parser.add_argument("--model", default="large-v3")
    parser.add_argument("--device", default="cpu")
    parser.add_argument("--compute-type", default="int8")
    parser.add_argument("--beam-size", type=int, default=5)
    parser.add_argument("--language", default="en")
    parser.add_argument("--expected-text", default="")
    args = parser.parse_args()

    try:
        from faster_whisper import WhisperModel  # type: ignore
    except Exception as exc:  # pragma: no cover
        print(json.dumps({"success": False, "error": f"faster_whisper import failed: {exc}"}, ensure_ascii=False))
        return 1

    if not os.path.isfile(args.audio_file):
        print(json.dumps({"success": False, "error": "audio file not found"}, ensure_ascii=False))
        return 1

    try:
        model = WhisperModel(
            args.model,
            device=args.device,
            compute_type=args.compute_type,
        )

        segments, info = model.transcribe(
            args.audio_file,
            language=(args.language or "en"),
            beam_size=max(1, int(args.beam_size)),
            condition_on_previous_text=False,
            vad_filter=True,
            temperature=0.0,
            initial_prompt=(args.expected_text or "").strip() or None,
        )

        transcript = " ".join((segment.text or "").strip() for segment in segments).strip()

        print(json.dumps({
            "success": True,
            "transcript": transcript,
            "language": getattr(info, "language", None),
        }, ensure_ascii=False))
        return 0
    except Exception as exc:  # pragma: no cover
        print(json.dumps({"success": False, "error": str(exc)}, ensure_ascii=False))
        return 1


if __name__ == "__main__":
    raise SystemExit(main())
