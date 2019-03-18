'''
May 2017
@author: Burkhard A. Meier
'''
# ======================
# imports
# ======================
import tkinter as tk
from tkinter import ttk, Frame
from tkinter import scrolledtext

# Create instance
win = tk.Tk()

# Add a title
win.title("Streaming App")
win.geometry('{}x{}'.format(700, 650))

top_frame = ttk.LabelFrame(win, text='Stream Screen')
bot_frame = ttk.LabelFrame(win, text='Transcribe')

top_frame.grid(row=0, sticky="ew")
bot_frame.grid(row=2, sticky="ew")

record_scr = scrolledtext.ScrolledText(top_frame, wrap=tk.WORD)
record_scr.grid(row=0, columnspan=2, padx=5, pady=5)

text = tk.Text(bot_frame, height=10, width=50, font=("bold", 10,))
text.grid(row=2, padx=5, pady=5)

RECORDING = False

def insert():
    text.configure(state='normal')
    text.insert(tk.END, "Bye Bye.....")
    text.configure(state='disabled')
    text.tag_add("here", "1.0", "1.7")
    text.tag_add("start", "1.8", "1.13")
    text.tag_config("here", background="yellow", foreground="blue")
    text.tag_config("start", background="black", foreground="green")

# config record audio
import alsaaudio, time, audioop

# Open the device in nonblocking capture mode. The last argument could
# just as well have been zero for blocking mode. Then we could have
# left out the sleep call in the bottom of the loop
inp = alsaaudio.PCM(alsaaudio.PCM_CAPTURE,alsaaudio.PCM_NONBLOCK)

# Set attributes: Mono, 8000 Hz, 16 bit little endian samples
inp.setchannels(1)
inp.setrate(8000)
inp.setformat(alsaaudio.PCM_FORMAT_S16_LE)

# The period size controls the internal number of frames per period.
# The significance of this parameter is documented in the ALSA api.
# For our purposes, it is suficcient to know that reads from the device
# will return this many frames. Each frame being 2 bytes long.
# This means that the reads below will return either 320 bytes of data
# or 0 bytes of data. The latter is possible because we are in nonblocking
# mode.
inp.setperiodsize(160)

# config record video
import numpy as np
import cv2

cap = cv2.VideoCapture(0)

# Modified Button Click Function
def click_me():
    global RECORDING
    insert()
    if record_btn['text'] == 'Stop':
        RECORDING = False
        record_btn.configure(text='Start')
    else:
        RECORDING = True
        record_btn.configure(text='Stop')
        while (RECORDING):
            # config audio
            l, data = inp.read()
            if l:
                # Return the maximum of the absolute value of all samples in a fragment.
                print(audioop.max(data, 2))

            # config video
            # Capture frame-by-frame
            ret, frame = cap.read()

            # Our operations on the frame come here
            gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

            # Display the resulting frame
            cv2.imshow('frame', gray)
            if cv2.waitKey(1) & 0xFF == ord('q'):
                break

        # When everything done, release the capture
        cap.release()
        cv2.destroyAllWindows()

# Adding a Button
record_btn = ttk.Button(bot_frame, text="Start", command=click_me)
record_btn.grid(row=2, column=3)

# ======================
# Start GUI
# ======================
win.mainloop()











