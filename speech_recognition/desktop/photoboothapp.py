# import the necessary packages
from __future__ import print_function
from PIL import Image
from PIL import ImageTk
import tkinter as tki
from tkinter import scrolledtext
import threading
import datetime
import imutils
import cv2
import os
from imutils.video import VideoStream
import time
import multiprocessing as mp
from PIL import ImageTk, Image

from audio import recordThread

from autocomplete_combobox import Combobox_Autocomplete

from language_code import langDict

class PhotoBoothApp:
    def __init__(self, vs):
        # store the video stream object and output path, then initialize
        # the most recently read frame, thread for reading frames, and
        # the thread stop event
        self.vs = vs
        self.frame = None
        self.thread = None
        self.stopEvent = None

        # initialize the root window and image panel
        self.root = tki.Tk()
        self.panel = None

        # start a thread that constantly pools the video sensor for
        # the most recently read frame
        self.stopEvent = threading.Event()

        # set a callback to handle when the window is closed
        self.root.wm_title("PyImageSearch PhotoBooth")
        self.root.wm_protocol("WM_DELETE_WINDOW", self.onClose)

        # Add a title
        self.root.title("Streaming App")
        self.root.geometry('{}x{}'.format(700, 500))

        self.top_frame = tki.LabelFrame(self.root, text='Stream Screen')
        self.bot_frame = tki.LabelFrame(self.root, text='Transcribe')

        self.top_frame.grid(row=0, sticky="ew", column=0)
        self.bot_frame.grid(row=2, sticky="ew", column=0)


        scrol_w = 69
        scrol_h = 15  # increase sizes
        self.scrol = scrolledtext.ScrolledText(self.bot_frame, width=scrol_w, height=scrol_h, wrap=tki.WORD)
        self.scrol.grid(column=0, row=3, sticky='WE')

        # Adding options
        self.option_frame = tki.LabelFrame(self.root, text='Options')
        self.option_frame.grid(row=0, column=1, sticky='N', padx=10)

        # Adding a Button
        self.record_btn = tki.Button(self.option_frame, text="Start", command=self.click_me)
        self.record_btn.grid(row=3, column=1, pady=(0,5))

        # init multiple language
        # self.mul_check_val = tki.IntVar()
        # self.mul_check = tki.Checkbutton(self.option_frame, text="Auto detect language", variable=self.mul_check_val, \
        #                  onvalue=1, offvalue=0, height=5, \
        #                  width=20)
        # self.mul_check.grid(row=0, column=1)

        # init combobox
        self.lang_label = tki.Label(self.option_frame, text="Select language")
        self.lang_label.grid(row=0, column=1)
        self.list_of_items = [lang for lang, code in langDict.items()]

        self.combobox_autocomplete = Combobox_Autocomplete(self.option_frame, self.list_of_items, highlightthickness=1)
        self.combobox_autocomplete.grid(row=1, column=1, pady=(0,10))

        # init off screen
        self.off_img = ImageTk.PhotoImage(Image.open("off_stream.png").resize((400, 220)))

        # init waiting screen
        self.wait_img = ImageTk.PhotoImage(Image.open("wait_screen.jpg").resize((400, 220)))
        self.panel = tki.Label(self.top_frame, image=self.wait_img)
        self.panel.pack(side="left", expand=tki.YES, fill=tki.BOTH, padx=10, pady=10)

    def click_me(self):
        if self.record_btn['text'] == 'Stop':
            global _FINISH
            _FINISH = True
            self.record_btn.configure(text='Start')
            self.stopEvent.set()
        else:
            if self.combobox_autocomplete.get_value():
                self.record_btn.configure(text='Stop')
                _new = threading.Thread(target=self.record_audio,args=())
                _new.start()
                self.thread = threading.Thread(target=self.videoLoop, args=())
                self.thread.start()
            else:
                from tkinter import messagebox
                messagebox.showinfo("Error", "Please choose a language.")

    def record_audio(self):
        while not self.stopEvent.is_set():
            thread1 = recordThread(self)
            thread1.daemon = True
            thread1.start()
            time.sleep(1)

    def videoLoop(self):
        # DISCLAIMER:
        # I'm not a GUI developer, nor do I even pretend to be. This
        # try/except statement is a pretty ugly hack to get around
        # a RunTime error that Tkinter throws due to threading
        try:
            # keep looping over frames until we are instructed to stop
            while not self.stopEvent.is_set():
                # grab the frame from the video stream and resize it to
                # have a maximum width of 300 pixels
                self.frame = self.vs.read()
                self.frame = imutils.resize(self.frame, width=300)

                # OpenCV represents images in BGR order; however PIL
                # represents images in RGB order, so we need to swap
                # the channels, then convert to PIL and ImageTk format
                image = cv2.cvtColor(self.frame, cv2.COLOR_BGR2RGB)
                image = Image.fromarray(image)
                image = ImageTk.PhotoImage(image)

                self.panel.configure(image=image)
                self.panel.image = image

            self.panel.configure(image=self.off_img)
            self.panel.image = self.off_img
        except RuntimeError:
            print("[INFO] caught a RuntimeError")

    def onClose(self):
        # set the stop event, cleanup the camera, and allow the rest of
        # the quit process to continue
        print("[INFO] closing...")
        self.stopEvent.set()
        self.vs.stop()
        self.root.quit()

if __name__ == "__main__":
    print("[INFO] warming up camera...")
    vs = VideoStream(usePiCamera=False).start()
    time.sleep(2.0)
    app = PhotoBoothApp(vs)
    app.root.mainloop()