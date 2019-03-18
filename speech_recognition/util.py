s1 = "cả phóng viên quốc tế đều được thưởng thức miễn phí nhiều Đặc sản nổi tiếng của Việt Nam như bún chả"
s2 = "như bún chả bún thang nêm phở cà phê chứng Do chính tay nghệ nhân ẩm thực nổi tiếng nhất chế biến"
s3 = "nhiều nhất chế biến Nhiều chuỗi quán cà phê quán ăn nổi tiếng của Hà Nội cũng mong muốn đây là cơ hội quảng bá ẩm thực Việt Nam"

def merge_arr(s1, s2):
    for i, w1 in enumerate(s1):
        for j, w2 in enumerate(s2):
            if w1 == w2:
                combine_arr = s1[:i] + s2[j:]
                return combine_arr

def join_sen(s1, s2):
    s1_tokens = s1.lower().split(' ')
    s2_tokens = s2.lower().split(' ')
    s1_heads = s1_tokens[:-5]
    s1_tails = s1_tokens[-5:]
    s2_heads = s2_tokens[:5]
    s2_tails = s2_tokens[5:]
    combine_arr = merge_arr(s1_tails, s2_heads)
    s1_split = s1.split(' ')[:-5]
    s2_split = s2.split(' ')[5:]
    full_arr = s1_split + combine_arr + s2_split
    full_sen = ' '.join(full_arr)
    return full_sen

