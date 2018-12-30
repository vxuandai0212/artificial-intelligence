#!/usr/bin/env python3
import json
import datetime
from math import log

# input file name: ./input/stockPrices_raw.json
# output file name: ./input/stockReturns.json
# json structure: crawl daily price data from yahoo finance
#          term (short/mid/long)
#         /         |         \
#   ticker A   ticker B   ticker C
#      /   \      /   \      /   \
#  date1 date2 date1 date2 date1 date2
#
# Note: short return: adjClose/open - 1
#       mid return: adjClose/adjClose(7 days ago) - 1
#       long return: adjClose/adjClose(28 days ago) - 1


# calc long/mid term influence
def calc_mid_long_return(ticker, date, delta, priceSet):
    baseDate = datetime.datetime.strptime(date, "%Y-%m-%d")
    prevDate = (baseDate - datetime.timedelta(days=delta)).strftime("%Y-%m-%d")
    nextDate = (baseDate + datetime.timedelta(days=1)).strftime("%Y-%m-%d")
    if delta == 1:
        wkday = baseDate.weekday()
        if wkday == 0: # Monday
            prevDate = (baseDate - datetime.timedelta(days=3)).strftime("%Y-%m-%d")
        elif wkday == 4: # Friday
            nextDate = (baseDate + datetime.timedelta(days=3)).strftime("%Y-%m-%d")
        elif wkday == 5: # Saturday
            prevDate = (baseDate - datetime.timedelta(days=1)).strftime("%Y-%m-%d")
            nextDate = (baseDate + datetime.timedelta(days=2)).strftime("%Y-%m-%d")
        elif wkday == 6: # Sunday
            prevDate = (baseDate - datetime.timedelta(days=2)).strftime("%Y-%m-%d")
            nextDate = (baseDate + datetime.timedelta(days=1)).strftime("%Y-%m-%d")

    try:
        if delta == 1:
            return_self = log(priceSet[ticker]['adjClose'][date]) - log(priceSet[ticker]['open'][date])
            return_sp500 = log(priceSet['^GSPC']['adjClose'][date]) - log(priceSet['^GSPC']['open'][date])
            #return_self = log(priceSet[ticker]['adjClose'][nextDate]) - log(priceSet[ticker]['adjClose'][prevDate])
            #return_sp500 = log(priceSet['^GSPC']['adjClose'][nextDate]) - log(priceSet['^GSPC']['adjClose'][prevDate])
        else:
            return_self = log(priceSet[ticker]['adjClose'][date]) - log(priceSet[ticker]['adjClose'][prevDate])
            return_sp500 = log(priceSet['^GSPC']['adjClose'][date]) - log(priceSet['^GSPC']['adjClose'][prevDate])
        return True, round(return_self - return_sp500, 4) # relative return
    except:
        return False, 0

def main():
    raw_price_file = 'input/stockPrices_raw.json'
    with open(raw_price_file) as file:
        print("Loading price info ...")
        priceSet = json.load(file)
        dateSet = priceSet['^GSPC']['adjClose'].keys()

    returns = {'short': {}, 'mid': {}, 'long': {}} # 1-depth dictionary
    for num, ticker in enumerate(priceSet):
        print(num, ticker)
        for term in ['short', 'mid', 'long']:
            returns[term][ticker] = {} # 2-depth dictionary
        for day in dateSet:
            date = datetime.datetime.strptime(day, "%Y-%m-%d").strftime("%Y%m%d") # change date 2014-01-01 to 20140101
            tag_short, return_short = calc_mid_long_return(ticker, day, 1, priceSet)
            tag_mid, return_mid = calc_mid_long_return(ticker, day, 7, priceSet)
            tag_long, return_long = calc_mid_long_return(ticker, day, 28, priceSet)
            if tag_short:
                returns['short'][ticker][date] = return_short
            if tag_mid:
                returns['mid'][ticker][date] = return_mid
            if tag_long:
                returns['long'][ticker][date] = return_long

    with open('./input/stockReturns.json', 'w') as outfile:
        json.dump(returns, outfile, indent=4)

if __name__ == "__main__":
    main()
