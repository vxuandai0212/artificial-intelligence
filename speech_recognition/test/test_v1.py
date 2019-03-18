raw = "Ngày hôm qua 28 tháng 2 hội đồng bảo an liên hợp quốc đã không thông qua cả 2 dự thảo nghị quyết về Venezuela lần lượt do hoa kì và nga bảo trợ vốn đối đầu nhau về cách giải quyết tình hình tại quốc gia Nam Mỹ này, dự thảo nghị quyết do Hoa Kì bảo trợ nhận được 9 phiếu ủng hộ, 3 phiếu chống và 3 phiếu trắng. Tại hội đồng gồm 15 nước ủy viên Nga và Trung Quốc phủ quyết dự thảo này, ít phút sau đó hội đồng bảo an bỏ phiếu đối với dự thảo nghị quyết do nga bảo trợ kết quả dự thảo nghị quyết này nhận được 4 phiếu ủng hộ 7 phiếu chống và 4 phiếu trắng"

_x05 = "Ngày Hôm Qua 28 tháng 2 Hội đồng Bảo an Liên Hợp Quốc đã không thông qua cả hai dự thảo Nghị quyết về với là lần lượt do Hoa kỳ và nga bảo trợ đối đầu nhau về cách giải quyết tình hình tại quốc gia nam mỹ này dự thảo nghị quyết do kỳ bảo trợ nhân được 9 phiếu chống và 3 phiếu trắng hội đồng gồm 15 nước ủy viên Nga và Trung Quốc Huyết dự thảo này ít phút sau đó hội đồng bà ăn bỏ phiếu đối với dự thảo nghị quyết do Nhã bảo trợ kết quả dự thảo nghị quyết này nhận được 4 phiếu Hồ 7 phiếu chống và bốn thiếu sáng"

_x075 = "Ngày Hôm Qua 28 tháng 2 Hội đồng Bảo an Liên Hợp Quốc đã không thông qua cả hai dự thảo Nghị quyết về với Sơn La lần lượt do Hoa Kỳ và Nga bảo trợ vốn đối đầu nhau về cách giải quyết tình hình tại quốc gia Nam Mỹ này dự thảo nghị quyết do Hoa Kỳ bảo trợ nhận được 9 phiếu ủng hộ 3 phiếu chống và 3 phiếu trắng hội đồng gồm 15 nước ủy viên Nga và Trung Quốc phủ quyết dự thảo này ít phút sau đó hội đồng bảo an bỏ phiếu đối với dự thảo nghị quyết do Nga bảo trợ kết quả dự thảo nghị quyết này nhận được 4 phiếu vô 7 phiếu chống và bốn tiêu trắng"

_x1 = "Ngày Hôm Qua 28 tháng 2 Hội đồng Bảo an Liên Hợp Quốc đã không thông qua cả hai dự thảo Nghị quyết về với Sơn La lần lượt sau Hoa Kỳ và Nga bảo trợ vốn đối đầu nhau về cách giải quyết tình hình tại Quốc dự thảo nghị quyết do hoa Kỳ bảo trợ nhận được 9 phiếu ủng hộ 3 phiếu chống và 3 phiếu trắng tại hội đồng gồm 15 nước ủy viên Nga và Trung Quốc phủ quyết dự thảo này ít phút sau đó Hội đồng Bảo an bỏ phiếu đối với dự thảo nghị quyết do Nga bảo trợ và dự thảo nghị quyết này nhận được 4 phiếu ủng hộ 7 phiếu chống và bốn chỗ trắng"

def nomalize(sen):
    nor_sen = sen.replace('.', '').replace(',', '').lower()
    nor_sen_arr = nor_sen.split(' ')
    print(nor_sen)
    print("total words: {}".format(len(nor_sen_arr)))

print("raw")
nomalize(raw)
print("0.5 speed")
nomalize(_x05)
print("0.75 speed")
nomalize(_x075)
print("1 speed")
nomalize(_x1)

"""
Đánh giá về dịch từ âm thanh sang text: tốc độ trung bình đạt hiệu quả tốt nhất
test lần 1
test một bản tin giọng nam độ dài 1 phút 30 giây (1'30 là thời lượng trung bình của một tin)
- ở tốc độ 0.5:
+ bắt được 96% bản tin (121/129 từ)
+ độ chính xác đạt khoảng trên 50%

- ở tốc độ 0.75 đem đến kết quả tốt nhất:
+ sai 4 trên 129 từ (đạt 97%): sai chủ yếu ở tên riêng nước ngoài, tên người, tên tổ chức

- ở tốc độ 1.0:
+ bắt được 98% bản tin (126/129 từ)
+ sai 4 trên 129 từ (đạt 97%): sai chủ yếu ở tên riêng nước ngoài, tên người, tên tổ chức
Đánh giá về phân tích cảm xúc:
- Cảm xúc không phụ thuộc vào người nói nói gì. Tuy nhiên, model train dùng dữ liệu giọng nói của 
người nước ngoài. Nên khi dự đoán giọng Việt hoàn toàn bị lệch.
"""