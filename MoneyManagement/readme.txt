_ Thông tin cá nhân: 1412544 - Phạm Đức Tiên - phamductien133@gmail.com - 01699367737
_ Đánh giá mức độ hoàn thành: 100% (Điểm công: Làm thêm popup menu, có file config.ini, Hiển thị ghi chú tên loại và %)
_ Những điều em đã làm:
   + Tự tạo form với hàm CreateWindow, form tự thiết lập được 
     các thông số về chiều cao, rộng, vị trí cũng như màu nền
   + Tạo được menu trên tabmenu của Form bằng hàm tự viết void AddMenus(HWND hwnd) với các thông tin: MSSV - 
     Họ tên - Lời cảm ơn khi sử dụng phần mềm này và tích hợp đó là nút Exit.
   + Tạo popup menu tương tự như menu (nhấp chuột phải vào màn hình để hiện popup menu)
   + Hoàn thành chức năng thêm chi tiêu
   + Hoàn thành chức năng Xem lại danh sách
   + Hoàn thành vẽ biểu đồ cột
   + có ghi chú rỏ ràng về % cũng nhu loại của từng màu biểu đồ
   + Các textbox mang tính chất hiển thị kết quả nằm ở dang read only như: Tổng tiền, % của loại,...
   + File config.ini để chỉnh size màn hình
_ Kịch bản chính (Thành công):
   + Chọn Loại chi tiêu là: Ăn uống
   + Điền vào textbox Nội dung: Cơm
   + Điền vào textbox Số tiền: 10000
   + Bấm thêm, thông báo thành công và cập nhật lại tổng tiền cũng như biểu đồ
_ Kịch bản phụ (Thất bại) thứ nhất:
   + Chọn Loại chi tiêu là: Di chuyển
   + textbox Nội dung: để trống
   + Điền vào textbox Số tiền: 10000
   + Bấm thêm, thông báo "Kiểm tra lại mục nội dung đã điền chưa"
_ Kịch bản phụ (Thất bại) thứ hai:
   + Chọn Loại chi tiêu là: Di chuyển
   + textbox Nội dung: Bus
   + Điền vào textbox Số tiền: để trống
   + Bấm thêm, thông báo "Kiểm tra lại mục Số tiền đã điền chưa"
_ Kịch bản phụ (Thất bại) thứ ba:
   + Chọn Loại chi tiêu là: Ăn uống
   + Điền vào textbox Nội dung: Cơm
   + Điền vào textbox Số tiền: Pham Duc Tien
   + Bấm thêm, thông báo "Chỉ điền integer cho số tiền"