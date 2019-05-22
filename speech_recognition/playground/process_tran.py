# -*- coding: utf-8 -*-
def merge_arr(s1, s2):
    for i, w1 in enumerate(s1):
        for j, w2 in enumerate(s2):
            if w1 == w2:
                combine_arr = s1[:i] + s2[j:]
                return combine_arr

def refactor_sen(s1, s2):
    s1_tokens = s1.lower().split(' ')
    s2_tokens = s2.lower().split(' ')
    s1_heads = s1_tokens[:-5]
    s1_tails = s1_tokens[-5:]
    s2_heads = s2_tokens[:5]
    s2_tails = s2_tokens[5:]
    combine_arr = merge_arr(s1_tails, s2_heads)
    s1_split = s1.split(' ')[:-5]
    s2_split = s2.split(' ')[5:]
    s1_refactor = ' '.join(s1_split + combine_arr)
    s2_refactor = ' '.join(s2_split)
    return s1_refactor, s2_refactor

current_tran = {"text": "", "emo": ""}
trans = [
    {"text": "1 He seems sinking under the evidence is wrong", "emo": "male_sad"},
    {"text": "the evidence is wrong this gonna right", "emo": "female_sad"},
    {"text": "it is gonna right of the note", "emo": "male_happy"}
]

def mer_tran(current_tran, trans):
    mer_trans = []
    if(current_tran["text"] == ""):
        return mer_trans

    if (len(trans) == 0):
        mer_trans.append(current_tran)
    else:
        last_tran = trans[-1]
        s1, s2 = refactor_sen(last_tran["text"], current_tran["text"])
        last_tran["text"] = s1
        current_tran["text"] = s2
        mer_trans = trans[:-1]
        mer_trans.append(last_tran)
        mer_trans.append(current_tran)

    return mer_trans


mer_tran = mer_tran(current_tran, trans)
print mer_tran