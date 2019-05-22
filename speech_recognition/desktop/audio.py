import sounddevice as sd
import soundfile as sf

import os
import sys
sys.path.insert(1, '/home/lam/Desktop/vini-intern/speech_recognition')
from speech_to_text.recognize import recognize_audio
import time
import threading
import tkinter as tki


samplerate = 44100  # Hertz
duration = 3  # seconds

class recordThread (threading.Thread):
   def __init__(self, app):
       threading.Thread.__init__(self)
       self.app = app
   def run(self):
       filename = str(time.time()) + 'output.wav'
       trans = record_then_trans(self.app.combobox_autocomplete.get_value(), filename)
       self.app.scrol.insert(tki.INSERT, trans + '\n')

def record_then_trans(lang, filename):
    # start_record = int(time.time())
    mydata = sd.rec(int(samplerate * duration), samplerate=samplerate,
                    channels=1, blocking=True)
    sf.write(filename, mydata, samplerate)
    # finish_record = int(time.time())
    if os.path.isfile(filename):
        trans = recognize_audio('/home/lam/Desktop/vini-intern/speech_recognition/desktop/' + filename, lang)
        # os.remove(filename)
        return trans
    #     print(trans)
    #     os.remove(filename)
    #     finish_trans = int(time.time())
    # print("record time {}".format(finish_record-start_record))
    # print("trans time {}".format(finish_trans-finish_record))

 