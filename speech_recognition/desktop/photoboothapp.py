# import the necessary packages
from __future__ import print_function
from PIL import Image
from PIL import ImageTk
import tkinter as tki
import threading
import datetime
import imutils
import cv2
import os
from imutils.video import VideoStream
import time
import multiprocessing as mp
import transcribe_streaming_mic as ts


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
        self.thread = threading.Thread(target=self.videoLoop, args=())
        self.thread.start()

        # record audio thread
        self.audio_process = mp.Process(target=self.record_audio, args=())

        # set a callback to handle when the window is closed
        self.root.wm_title("PyImageSearch PhotoBooth")
        self.root.wm_protocol("WM_DELETE_WINDOW", self.onClose)

        # Add a title
        self.root.title("Streaming App")
        self.root.geometry('{}x{}'.format(700, 650))

        self.top_frame = tki.LabelFrame(self.root, text='Stream Screen')
        self.bot_frame = tki.LabelFrame(self.root, text='Transcribe')

        self.top_frame.grid(row=0, sticky="ew")
        self.bot_frame.grid(row=2, sticky="ew")

        self.trans = tki.Text(self.bot_frame, height=10, width=50, font=("bold", 10,))
        self.trans.grid(row=2, padx=5, pady=5)
        self.trans.configure(state='disabled')

        # Adding a Button
        self.record_btn = tki.Button(self.bot_frame, text="Start", command=self.click_me)
        self.record_btn.grid(row=2, column=3)

    # def insert(self):
    #     self.trans.configure(state='normal')
    #     self.trans.insert(tki.END, 'hi')
    #     self.trans.configure(state='disabled')
        # self.trans.tag_add("here", "1.0", "1.7")
        # self.trans.tag_add("start", "1.8", "1.13")
        # self.trans.tag_config("here", background="yellow", foreground="blue")
        # self.trans.tag_config("start", background="black", foreground="green")

    def click_me(self):
        if self.record_btn['text'] == 'Stop':
            self.record_btn.configure(text='Start')
            self.audio_process.terminate()
        else:
            self.record_btn.configure(text='Stop')
            self.audio_process = mp.Process(target=self.record_audio, args=())
            self.audio_process.start()
            self.trans.configure(state='normal')
            self.trans.insert(tki.END, 'fasdf')
            self.trans.configure(state='disabled')

    # config record audio
    def record_audio(self):
        print(self.record_btn['text'])
        # ts.main()
        import test as t
        rs = t.insert(self.trans)
        self.trans.configure(state='normal')
        self.trans.insert(tki.END, rs)
        self.trans.configure(state='disabled')
        self.root.update()
        print(rs)

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

                # if the panel is not None, we need to initialize it
                if self.panel is None:
                    self.panel = tki.Label(self.top_frame, image=image)
                    self.panel.image = image
                    self.panel.pack(side="left", padx=10, pady=10)

                # otherwise, simply update the panel
                else:
                    self.panel.configure(image=image)
                    self.panel.image = image

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