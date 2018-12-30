import sys
import csv
from utils import write_status

LABEL_TEST_FILE = './processed_test_data - Copy.csv'
PREDICTED_FILE = './cnn.csv'

label_tweets = []
predict_tweets = []
correct = 0

with open(LABEL_TEST_FILE, 'r', encoding="utf8") as csvfile:
    csvreader = csv.reader(csvfile)
    for row in csvreader:
        label_tweets.append(row)

with open(PREDICTED_FILE, 'r', encoding="utf8") as csvfile:
    csvreader = csv.reader(csvfile)
    for row in csvreader:
        predict_tweets.append(row)

predict_tweets = predict_tweets[1:]
total = len(label_tweets)

counter = 0
for truth, predict in zip(label_tweets, predict_tweets):
    if truth[1] == predict[1]:
        correct += 1
    write_status(correct, total)

print("Correct: {} %".format((correct/total) * 100))