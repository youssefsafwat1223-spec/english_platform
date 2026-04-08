#!/usr/bin/env python3
"""
Assess one uploaded audio file with Azure Speech Pronunciation Assessment and return JSON.
"""

from __future__ import annotations

import argparse
import json
import os


def _enum_value(module, enum_name: str, fallback):
    return getattr(module, enum_name, fallback)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--audio-file", required=True)
    parser.add_argument("--speech-key", required=True)
    parser.add_argument("--speech-region", required=True)
    parser.add_argument("--language", default="en-US")
    parser.add_argument("--expected-text", required=True)
    parser.add_argument("--grading-system", default="HundredMark")
    parser.add_argument("--granularity", default="Phoneme")
    parser.add_argument("--enable-miscue", default="true")
    parser.add_argument("--enable-prosody", default="false")
    args = parser.parse_args()

    if not os.path.isfile(args.audio_file):
        print(json.dumps({"success": False, "error": "audio file not found"}, ensure_ascii=False))
        return 1

    try:
        import azure.cognitiveservices.speech as speechsdk  # type: ignore
    except Exception as exc:  # pragma: no cover
        print(json.dumps({"success": False, "error": f"azure speech import failed: {exc}"}, ensure_ascii=False))
        return 1

    try:
        speech_config = speechsdk.SpeechConfig(subscription=args.speech_key, region=args.speech_region)
        speech_config.speech_recognition_language = args.language or "en-US"
        speech_config.output_format = speechsdk.OutputFormat.Detailed

        audio_config = speechsdk.audio.AudioConfig(filename=args.audio_file)
        recognizer = speechsdk.SpeechRecognizer(speech_config=speech_config, audio_config=audio_config)

        grading_system = _enum_value(
            speechsdk.PronunciationAssessmentGradingSystem,
            args.grading_system,
            speechsdk.PronunciationAssessmentGradingSystem.HundredMark,
        )
        granularity = _enum_value(
            speechsdk.PronunciationAssessmentGranularity,
            args.granularity,
            speechsdk.PronunciationAssessmentGranularity.Phoneme,
        )
        enable_miscue = str(args.enable_miscue).strip().lower() in {"1", "true", "yes", "on"}
        enable_prosody = str(args.enable_prosody).strip().lower() in {"1", "true", "yes", "on"}

        pronunciation_config = speechsdk.PronunciationAssessmentConfig(
            reference_text=args.expected_text,
            grading_system=grading_system,
            granularity=granularity,
            enable_miscue=enable_miscue,
        )

        if enable_prosody and hasattr(pronunciation_config, "enable_prosody_assessment"):
            pronunciation_config.enable_prosody_assessment()

        pronunciation_config.apply_to(recognizer)

        result = recognizer.recognize_once()

        if result.reason != speechsdk.ResultReason.RecognizedSpeech:
            details = None
            if result.reason == speechsdk.ResultReason.NoMatch:
                details = "no_match"
            elif result.reason == speechsdk.ResultReason.Canceled:
                cancellation = speechsdk.CancellationDetails(result)
                details = {
                    "reason": str(cancellation.reason),
                    "error_details": cancellation.error_details,
                }

            print(json.dumps({
                "success": False,
                "error": "speech_not_recognized",
                "details": details,
            }, ensure_ascii=False))
            return 1

        json_result = result.properties.get(
            speechsdk.PropertyId.SpeechServiceResponse_JsonResult
        )

        raw = json.loads(json_result) if json_result else {}
        nbest = (raw.get("NBest") or [{}])[0]
        pa = nbest.get("PronunciationAssessment") or {}

        response = {
            "success": True,
            "recognized_text": result.text or nbest.get("Display") or "",
            "accuracy_score": pa.get("AccuracyScore"),
            "fluency_score": pa.get("FluencyScore"),
            "completeness_score": pa.get("CompletenessScore"),
            "pronunciation_score": pa.get("PronScore"),
            "prosody_score": pa.get("ProsodyScore"),
            "words": nbest.get("Words") or [],
            "raw_result": raw,
        }

        print(json.dumps(response, ensure_ascii=False))
        return 0
    except Exception as exc:  # pragma: no cover
        print(json.dumps({"success": False, "error": str(exc)}, ensure_ascii=False))
        return 1


if __name__ == "__main__":
    raise SystemExit(main())
