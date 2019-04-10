import pyaudio
import wave
from array import array
import time
import threading
from multiprocessing import Process
from concurrent.futures import ThreadPoolExecutor

FORMAT=pyaudio.paInt16
CHANNELS=1
RATE=44100
CHUNK=1024
RECORD_SECONDS=6

def record_1():
    # instantiate the pyaudio
    audio_1 = pyaudio.PyAudio()
    # recording prerequisites
    stream_1 = audio_1.open(format=FORMAT, channels=CHANNELS,
                          rate=RATE,
                          input=True,
                          frames_per_buffer=CHUNK)
    FILE_NAME_1 = "RECORDING1.wav"

    #starting recording
    frames_1 = []

    for i in range(0,int(RATE/CHUNK*RECORD_SECONDS)):
        data_1=stream_1.read(CHUNK)
        data_chunk_1=array('h',data_1)
        vol=max(data_chunk_1)
        if(vol>=500):
            print("something said")
            frames_1.append(data_1)
        else:
            print("nothing")
        print("\n")

    # writing to file
    wavfile=wave.open(FILE_NAME_1,'wb')
    wavfile.setnchannels(CHANNELS)
    wavfile.setsampwidth(audio_1.get_sample_size(FORMAT))
    wavfile.setframerate(RATE)
    wavfile.writeframes(b''.join(frames_1))#append frames recorded to file
    wavfile.close()

    # end of recording
    stream_1.stop_stream()
    stream_1.close()
    audio_1.terminate()

thread1 = threading.Thread(target=record_1())
thread1.start()