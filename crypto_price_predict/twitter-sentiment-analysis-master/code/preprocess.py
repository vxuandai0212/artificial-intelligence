import re
import sys
from utils import write_status
from nltk.stem.porter import PorterStemmer
import uuid
import csv


def preprocess_word(word):
    # Remove punctuation
    word = word.strip('\'"?!,.():;')
    # Convert more than 2 letter repetitions to 2 letter
    # funnnnny --> funny
    word = re.sub(r'(.)\1+', r'\1\1', word)
    # Remove - & '
    word = re.sub(r'(-|\')', '', word)
    return word


def is_valid_word(word):
    # Check if word begins with an alphabet
    return (re.search(r'^[a-zA-Z][a-z0-9A-Z\._]*$', word) is not None)


def handle_emojis(tweet):
    # Smile -- :), : ), :-), (:, ( :, (-:, :')
    tweet = re.sub(r'(:\s?\)|:-\)|\(\s?:|\(-:|:\'\))', ' EMO_POS ', tweet)
    # Laugh -- :D, : D, :-D, xD, x-D, XD, X-D
    tweet = re.sub(r'(:\s?D|:-D|x-?D|X-?D)', ' EMO_POS ', tweet)
    # Love -- <3, :*
    tweet = re.sub(r'(<3|:\*)', ' EMO_POS ', tweet)
    # Wink -- ;-), ;), ;-D, ;D, (;,  (-;
    tweet = re.sub(r'(;-?\)|;-?D|\(-?;)', ' EMO_POS ', tweet)
    # Sad -- :-(, : (, :(, ):, )-:
    tweet = re.sub(r'(:\s?\(|:-\(|\)\s?:|\)-:)', ' EMO_NEG ', tweet)
    # Cry -- :,(, :'(, :"(
    tweet = re.sub(r'(:,\(|:\'\(|:"\()', ' EMO_NEG ', tweet)
    return tweet


def preprocess_tweet(tweet):
    processed_tweet = []
    # Convert to lower case
    tweet = tweet.lower()
    # Replaces URLs with the word URL
    tweet = re.sub(r'((www\.[\S]+)|(https?://[\S]+))', ' URL ', tweet)
    # Replace @handle with the word USER_MENTION
    tweet = re.sub(r'@[\S]+', 'USER_MENTION', tweet)
    # Replaces #hashtag with hashtag
    tweet = re.sub(r'#(\S+)', r' \1 ', tweet)
    # Remove RT (retweet)
    tweet = re.sub(r'\brt\b', '', tweet)
    # Replace 2+ dots with space
    tweet = re.sub(r'\.{2,}', ' ', tweet)
    # Strip space, " and ' from tweet
    tweet = tweet.strip(' "\'')
    # Replace emojis with either EMO_POS or EMO_NEG
    tweet = handle_emojis(tweet)
    # Replace multiple spaces with a single space
    tweet = re.sub(r'\s+', ' ', tweet)
    words = tweet.split()

    for word in words:
        word = preprocess_word(word)
        if is_valid_word(word):
            processed_tweet.append(word)

    return ' '.join(processed_tweet)


def preprocess_csv(csv_file_name):
    train = "processed_train_data.csv"
    test = "processed_test_data.csv"
    rows = []
    with open(csv_file_name, 'r', encoding="utf8") as csvfile:
        csvreader = csv.reader(csvfile)
        for row in csvreader:
            rows.append(row)
    # Chỉ lấy các tweet positive và negative
    tweets = [row for row in rows if "neutral" not in row[7]]
    # 20000 train
    # ~10000 test
    train_tweets = tweets[:20000]
    test_tweets = tweets[20000:]
    total = len(tweets)
    success = 0
    save_to_file = open(train, 'w', encoding="utf8")
    for i, tweet in enumerate(train_tweets):
        tweet_content = tweet[1]
        positive = 0 if "positive" not in tweet[7] else 1
        tweet_id = uuid.uuid4()
        processed_tweet = preprocess_tweet(tweet_content)
        save_to_file.write('%s,%d,%s\n' %(tweet_id, positive, processed_tweet))
        success += 1
        write_status(success, total)
    save_to_file.close()

    save_to_file = open(test, 'w', encoding="utf8")
    for i, tweet in enumerate(test_tweets):
        tweet_content = tweet[1]
        positive = 0 if "positive" not in tweet[7] else 1
        tweet_id = uuid.uuid4()
        processed_tweet = preprocess_tweet(tweet_content)
        save_to_file.write('%s,%d,%s\n' %(tweet_id, positive, processed_tweet))
        success += 1
        write_status(success, total)
    save_to_file.close()

    print('\nSaved processed tweets to: %s\n%s' % (test, train))


if __name__ == '__main__':
    csv_file_name = sys.argv[1]
    preprocess_csv(csv_file_name)
