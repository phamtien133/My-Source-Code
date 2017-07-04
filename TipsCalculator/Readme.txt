_ Thông tin cá nhân: 1412544 - Phạm Đức Tiên - phamductien133@gmail.com - 01699367737
_ Những điều em đã làm:
   + Tự tạo form với hàm CreateWindow, form tự thiết lập được 
     các thông số về chiều cao, rộng, vị trí cũng như màu nền
   + Tạo được menu trên tabmenu của Form bằng hàm tự viết void AddMenus(HWND hwnd) với các thông tin: MSSV - 
     Họ tên - Lời cảm ơn khi sử dụng phần mềm này và tích hợp đó là nút Exit.
   + Trong case WM_Create: tạo ra 2 label, 3 textbox trong đó có 1 textbox là only 
     read (Chỉ để xuất kết quả không điền được), 1 button với sự kiện là tính ra số tiền phải trả
     của từng người.
   + Trong case WM_COMMAND: xử lý case IDM_FILE_QUIT trên menu với chức năng là Exit (DestroyWindow(hWnd));
     đối với case DC_BUTTONS (button tính chi phí mỗi người) xử lý việc nhập số lượng người,tổng chi phí 
     và xuất kết quả là số tiền ($/person) nếu mọi thông số đều đúng, ngược lại báo lỗi.
   + Giao diện hài hòa, bắt mắt, xử lý được phần lớn các lỗi.
_ Kịch bản chính (Thành công):
   + Điền vào textbox Total Bill Cost (Tổng chi phí) là 20000
   + Điền vào textbox Number Of Guests (số khách) là 5
   + Tại textbox Result sẽ xuất ra kết quả: 4400 $/person ((20000 + 0.1 * 20000)/5)
_ Kịch bản phụ (Thất bại) thứ nhất:
   + Tại textbox Total Bill Cost (Tổng chi phí) để trống
   + Tại textbox Number Of Guests (số khách) điền số 10
   + Tại textbox Result sẽ xuất ra kết quả: Please fill integer number for Total Bill Cost
     (Nhắc nhỡ việc điền chi phí)
_ Kịch bản phụ (Thất bại) thứ hai:
   + Tại textbox Total Bill Cost (Tổng chi phí) điền số 10
   + Tại textbox Number Of Guests (số khách) để trống
   + Tại textbox Result sẽ xuất ra kết quả: Please fill integer number for Number Of Guests
     (Nhắc nhỡ việc điền số khách)
_ Kịch bản phụ thứ 3:
   + Tại textbox Total Bill Cost (Tổng chi phí) để trống
   + Tại textbox Number Of Guests (số khách) để trống
   + Tại textbox Result sẽ xuất ra kết quả: Please fill integer number for 2 textbox
     (Nhắc nhỡ việc điền 2 thông tin)
_ Kịch bản phụ thứ 4:
   + Tại textbox Total Bill Cost (Tổng chi phí) điền số 10
   + Tại textbox Number Of Guests (số khách) điền ' '(BackSpace) hoặc chữ hoặc các dấu >, <, ...
   + Tại textbox Result sẽ xuất ra kết quả: Only fill integer number in textbox Number Of Guests
     (Nhắc nhỡ chỉ điền số nguyên cho số khách)
_ Kịch bản phụ thứ 5:
   + Tại textbox Total Bill Cost (Tổng chi phí) điền ' '(BackSpace) hoặc chữ hoặc các dấu >, <, ...
   + Tại textbox Number Of Guests (số khách) điền số 10
   + Tại textbox Result sẽ xuất ra kết quả: Only fill integer number in textbox Total Bill Cost
     (Nhắc nhỡ chỉ điền số nguyên cho chi phí)
_ Kịch bản phụ thứ 6:
   + Tại textbox Total Bill Cost (Tổng chi phí) điền số 10
   + Tại textbox Number Of Guests (số khách) điền số 0
   + Tại textbox Result sẽ xuất ra kết quả: Number Of Guests must != 0
     (Nhắc nhỡ số khách phải khác 0)