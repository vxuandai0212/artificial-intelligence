import tkinter as tki

def insert(text):
    print('@@')
    text.configure(state='normal')
    text.insert(tki.END, 'hi')
    text.configure(state='disabled')
    return "ec ec"