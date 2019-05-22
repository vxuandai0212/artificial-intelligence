import io
import os

# Imports the Google Cloud client library
from google.cloud import speech
from google.cloud.speech import enums
from google.cloud.speech import types

# Instantiates a client
client = speech.SpeechClient()

def recognize_audio(filepath, lang):
    with io.open(filepath, 'rb') as audio_file:
        content = audio_file.read()

    audio = types.RecognitionAudio(content=content)
    config = types.RecognitionConfig(
        encoding=enums.RecognitionConfig.AudioEncoding.LINEAR16,
        # sample_rate_hertz=16000,
        language_code='vi-VN') # vi-VN, en-US, ja-JP

    # Detects speech in the audio file
    try:
        response = client.recognize(config, audio)
        return response.results[0].alternatives[0].transcript
    except:
        return ''
    # for result in response.results:
    #     print('Transcript: {}'.format(result.alternatives[0].transcript))

# def recognize_audio(file):
#     # Loads the audio into memory
#     with io.open(file, 'rb') as audio_file:
#         content = audio_file.read()
#         audio = types.RecognitionAudio(content=content)
#
#     config = types.RecognitionConfig(
#         encoding=enums.RecognitionConfig.AudioEncoding.LINEAR16,
#         # sample_rate_hertz=16000,
#         language_code='vi-VN')
#
#     # Detects speech in the audio file
#     response = client.recognize(config, audio)
#     return response.results[0].alternatives[0].transcript
#     for result in response.results:
#         print('Transcript: {}'.format(result.alternatives[0].transcript))