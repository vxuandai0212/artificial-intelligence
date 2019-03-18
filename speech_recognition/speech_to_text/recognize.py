# import speech_recognition as sr
#
# # obtain path to "english.wav" in the same folder as this script
# # AUDIO_FILE = path.join(path.dirname(path.realpath(__file__)), "french.aiff")
# # AUDIO_FILE = path.join(path.dirname(path.realpath(__file__)), "chinese.flac")
#
# # use the audio file as the audio source
# r = sr.Recognizer()
#
# def recognize_audio(file):
#     with sr.AudioFile(file) as source:
#         audio = r.record(source)  # read the entire audio file
#
#     # recognize speech using Google Speech Recognition
#     try:
#         # for testing purposes, we're just using the default API key
#         # to use another API key, use `r.recognize_google(audio, key="GOOGLE_SPEECH_RECOGNITION_API_KEY")`
#         # instead of `r.recognize_google(audio)`
#         result = r.recognize_google(audio)
#         return result
#
#     except sr.UnknownValueError:
#         print("Google Speech Recognition could not understand audio")
#     except sr.RequestError as e:
#         print("Could not request results from Google Speech Recognition service; {0}".format(e))

import io
import os

# Imports the Google Cloud client library
from google.cloud import speech
from google.cloud.speech import enums
from google.cloud.speech import types

# Instantiates a client
client = speech.SpeechClient()

def recognize_audio(file):
    file_name = '/home/lam/Desktop/vini-intern/speech_recognition/' + file
    # Loads the audio into memory
    with io.open(file_name, 'rb') as audio_file:
        content = audio_file.read()
        audio = types.RecognitionAudio(content=content)

    config = types.RecognitionConfig(
        encoding=enums.RecognitionConfig.AudioEncoding.LINEAR16,
        # sample_rate_hertz=16000,
        language_code='vi-VN')

    # Detects speech in the audio file
    response = client.recognize(config, audio)
    return response.results[0].alternatives[0].transcript
    for result in response.results:
        print('Transcript: {}'.format(result.alternatives[0].transcript))