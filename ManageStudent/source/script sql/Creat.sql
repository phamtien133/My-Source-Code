create database QLHOCSINH
go

use QLHOCSINH
go

--============================================== TẠO BẢNG ==============================================
create table NIENKHOA
(
	MANK char(5) primary key,
	TENNK char(9) not null unique
)

create table KHOI
(
	MAKHOI tinyint check( MAKHOI in (10, 11, 12)),
	NIENKHOA char(5),
	SOLOP tinyint not null,
	SSTĐ tinyint default 40,
	TUOITOITHIEU tinyint default 15 ,
	TUOITOIDA tinyint default 20,
	primary key (MAKHOI, NIENKHOA)
)

create table HOCSINH
(
	MAHS char(7),
	HOTENHS nvarchar(31) not null,
	NGAYSINH date not null,
	GIOITINH nvarchar(3) not null check ( GIOITINH in ( 'Nam', N'Nữ' )),
	DIACHI nvarchar(50) not null,
	EMAIL varchar(50),
	KHOI tinyint not null,
	NIENKHOA char(5) not null,
	primary key (MAHS)
)

create table MONHOC
(
	MAMH nvarchar(5) primary key,
	TENMH nvarchar(30) not null,
	HESO tinyint not null check( HESO in ( 1, 2))
)

create table GIAOVIEN
(
	MAGV char(5) primary key,
	HOTENGV nvarchar(31) not null,
	CMND char(10) not null unique,
	MAMH nvarchar(5) not null
)

create table LOP
(
	MALOP char(5) not null,
	NIENKHOA char(5) not null,
	KHOI tinyint not null,
	TENLOP nvarchar(20) not null,
	SISO tinyint not null,
	GVCN char(5),
	primary key (MALOP, NIENKHOA)
)

create table KIEMTRA
(
	MAKT varchar(3) primary key,
	TENKT nvarchar(10) not null unique,
	HESO tinyint not null check(HESO in ( 1, 2, 3))
)

create table SOLANKT
(
	MAKT varchar(3) not null,
	MAMH nvarchar(5) not null,
	SOLAN tinyint not null,
	primary key ( MAKT, MAMH)
)

create table HOCKY 
(
	MAHK char primary key check ( MAHK in ('1', '2')),
	TENHK nvarchar(10) unique,
	HESO tinyint not null
)

create table GV_LOP
(
	MALOP char(5),
	MAGV char(5),
	HOCKY char,
	NIENKHOA char(5),
	primary key (MALOP, MAGV, HOCKY, NIENKHOA)
)

create table DIEMKT
(
	MAHS char(7),
	MALOP char(5),
	NIENKHOA char(5),
	MAMH nvarchar(5),
	MAKT varchar(3),
	LANKT tinyint,
	HOCKY char(1),
	DIEM decimal(3,1) not null check( DIEM between 0.0 and 10.0 )
	primary key (MAHS, MALOP, NIENKHOA, MAMH, MAKT, HOCKY, LANKT)
)

create table DIEMTBM
(
	MAHS char(7),
	MALOP char(5),
	NIENKHOA char(5),
	MAMH nvarchar(5),
	HOCKY char(1),
	DTB decimal(3,1) not null check(DTB between 0.0 and 10.0),
	primary key (MAHS, MALOP, NIENKHOA, MAMH, HOCKY)
)

create table DIEMTBM_CN
(
	MAHS char(7),
	MALOP char(5),
	NIENKHOA char(5),
	MAMH nvarchar(5),
	DTB decimal(3,1) not null check(DTB between 0.0 and 10.0),
	primary key (MAHS, MALOP, NIENKHOA, MAMH)
)

create table HOCLUC
(
	MAHL varchar(3) primary key,
	TENHL nvarchar(10) unique,
	DIEMCANTREN decimal(3,1) check( DIEMCANTREN between 0.0 and 10.0),
	DIEMCANDUOI decimal(3,1) check( DIEMCANDUOI between 0.0 and 10.0)
)

create table XEPLOAI
(
	MALOAI varchar(3) primary key,
	TENLOAI nvarchar(10) not null unique
)

create table HANHKIEM
(
	MAHANHKIEM varchar(3) primary key,
	TENHANHKIEM nvarchar(10) unique
)

create table KQHT_HK
(
	MAHS char(7),
	MALOP char(5),
	NIENKHOA char(5),
	HOCKY char(1),
	DIEMTB decimal(3,1) check( DIEMTB between 0 and 10),
	HOCLUC varchar(3),
	HANHKIEM varchar(3),
	XEPLOAI varchar(3),
	primary key (MAHS, MALOP, NIENKHOA, HOCKY)
)

create table KQHT_CN
(
	MAHS char(7),
	MALOP char(5),
	NIENKHOA char(5),
	DIEMTB decimal(3,1) check( DIEMTB between 0 and 10),
	HOCLUC varchar(3),
	HANHKIEM varchar(3),
	XEPLOAI varchar(3),
	KETQUA nvarchar(20),
	primary key (MAHS, MALOP, NIENKHOA)
)

create table NHANVIEN
(
	MANV char(5) primary key,
	TENNV nvarchar(31),
	CMND char(10) unique,
	LOAINV bit not null
)

create table NGUOIDUNG
(
	TENTK char(5) primary key,
	MATKHAU varchar(30),
	LOAI bit
)

--================================================ THAM CHIẾU =================================================
-- Khóa ngoại của bảng HOCSINH
alter table HOCSINH add
	constraint FK_HOCSINH_KHOI foreign key (KHOI,NIENKHOA) references KHOI(MAKHOI, NIENKHOA) on delete cascade on update cascade 

-- GV.MAMH tham chiếu đến BM.MAMH
alter table GIAOVIEN add
	constraint FK_GV_MH foreign key (MAMH) references MONHOC(MAMH) on delete cascade on update cascade,
	constraint FK_GV_NGUOIDUNG foreign key (MAGV) references NGUOIDUNG(TENTK) on delete cascade on update cascade
	
-- Khóa ngoại của bảng LOP
alter table LOP add
	constraint FK_LOP_KHOI foreign key (KHOI,NIENKHOA) references KHOI(MAKHOI,NIENKHOA) on delete cascade on update cascade, 
	constraint FK_LOP_GIAOVIEN foreign key (GVCN) references GIAOVIEN(MAGV) on delete no action on update no action  
	
-- Khóa ngoại của bảng SOLANKT
alter table SOLANKT add
	constraint FK_SOLANKT_KIEMTRA foreign key (MAKT) references KIEMTRA(MAKT) on delete cascade on update cascade,
	constraint FK_SOLANKT_MONHOC foreign key (MAMH) references MONHOC(MAMH) on delete cascade on update cascade

-- Khóa ngoại cho bảng GV_LOP
alter table GV_LOP add
	constraint FK_GVLOP_LOP foreign key (MALOP,NIENKHOA) references LOP(MALOP,NIENKHOA) on delete cascade on update cascade,
	constraint FK_GVLOP_GIAOVIEN foreign key (MAGV) references GIAOVIEN(MAGV) on delete no action on update no action,
	constraint FK_GVLOP_HOCKY foreign key (HOCKY) references HOCKY(MAHK) on delete no action on update cascade

-- Khóa ngoại của bảng KHOI
alter table KHOI add
	constraint FK_KHOI_NIENKHOA foreign key (NIENKHOA) references NIENKHOA(MANK) on delete cascade on update cascade

-- Khóa ngoại của bảng DIEMKT
alter table DIEMKT add
	constraint FK_DIEMKT_HOCSINH foreign key (MAHS) references HOCSINH(MAHS) on delete no action on update no action,
	constraint FK_DIEMKT_LOP foreign key (MALOP, NIENKHOA) references LOP(MALOP, NIENKHOA) on delete cascade on update cascade,
	constraint FK_DIEMKT_MONHOC foreign key (MAMH) references MONHOC(MAMH) on delete cascade on update cascade,
	constraint FK_DIEMKT_KIEMTRA foreign key (MAKT) references KIEMTRA(MAKT) on delete cascade on update cascade,
	constraint FK_DIEMKT_HOCKY foreign key (HOCKY) references HOCKY(MAHK)  on delete cascade on update cascade

-- Khóa ngoại của bảng DTBMON
alter table DiemTBM add
	constraint FK_DTBMON_HOCSINH foreign key (MAHS) references HOCSINH(MAHS) on delete no action on update no action,
	constraint FK_DTBMON_LOP foreign key (MALOP, NIENKHOA) references LOP(MALOP, NIENKHOA) on delete cascade on update cascade,
	constraint FK_DTBMON_MONHOC foreign key (MAMH) references MONHOC(MAMH) on delete no action on update cascade,
	constraint FK_DTBMON_HOCKY foreign key (HOCKY) references HOCKY(MAHK)  on delete no action on update cascade

-- Khóa ngoại của bảng DIEMTBM_CN
alter table DIEMTBM_CN add
	constraint FK_DIEMTBMON_HOCSINH foreign key (MAHS) references HOCSINH(MAHS)  on delete no action on update no action,
	constraint FK_DIEMTBMON_LOP foreign key (MALOP,NIENKHOA) references LOP(MALOP,NIENKHOA) on delete cascade on update cascade,
	constraint FK_DIEMTBMON_MONHOC foreign key (MAMH) references MONHOC(MAMH) on delete no action on update cascade

-- Khóa ngoại cho bảng KQHT_HK
alter table KQHT_HK add
	constraint FK_KQHTHK_HOCSINH foreign key (MAHS) references HOCSINH(MAHS) on delete no action on update no action,
	constraint FK_KQHTHK_LOP foreign key (MALOP, NIENKHOA) references LOP(MALOP, NIENKHOA) on delete cascade on update cascade,
	constraint FK_KQHTHK_HOCKY foreign key (HOCKY) references HOCKY(MAHK)  on delete no action on update cascade,
	constraint FK_KQHTHK_HOCLUC foreign key (HOCLUC) references HOCLUC(MAHL) on delete cascade on update cascade,
	constraint FK_KQHTHK_HANHKIEM foreign key (HANHKIEM) references HANHKIEM(MAHANHKIEM) on delete cascade on update cascade,
	constraint FK_KQHTHK_XEPLOAI foreign key (XEPLOAI) references XEPLOAI(MALOAI) on delete cascade on update cascade
	
-- Khóa ngoại cho bảng KQHT_CN
 alter table KQHT_CN add
	constraint FK_KQHTCN_HOCSINH foreign key (MAHS) references HOCSINH(MAHS)  on delete no action on update no action,
	constraint FK_KQHTCN_LOP foreign key (MALOP, NIENKHOA) references LOP(MALOP, NIENKHOA) on delete cascade on update cascade,
	constraint FK_KQHTCN_HOCLUC foreign key (HOCLUC) references HOCLUC(MAHL) on delete cascade on update cascade,
	constraint FK_KQHTCN_HANHKIEM foreign key (HANHKIEM) references HANHKIEM(MAHANHKIEM) on delete cascade on update cascade,
	constraint FK_KQHTCN_XEPLOAI foreign key (XEPLOAI) references XEPLOAI(MALOAI) on delete cascade on update cascade

alter table NHANVIEN add
	constraint FK_NHANVIEN_NGUOIDUNG foreign key (MANV) references NGUOIDUNG(TENTK) on delete cascade on update cascade

--=============================================== NHẬP LIỆU ==================================================
insert into HANHKIEM values ('T',N'Tốt')
insert into HANHKIEM values ('K',N'Khá')
insert into HANHKIEM values ('TB',N'Trung bình')
insert into HANHKIEM values ('Y',N'Yếu')

--------------------------------------------------------------------------------------------------------------
insert into HOCKY values ('1', N'Học kỳ 1', 1)
insert into HOCKY values ('2', N'Học kỳ 2', 2)

--------------------------------------------------------------------------------------------------------------
insert into HOCLUC values ('G',N'Giỏi',10.0,8.0)
insert into HOCLUC values ('K',N'Khá',7.9,6.5)
insert into HOCLUC values ('TB',N'Trung bình',6.4,5.0)
insert into HOCLUC values ('Y',N'Yếu',4.9,3.5)
insert into HOCLUC values ('Kem',N'Kém',3.4,0.0)

--------------------------------------------------------------------------------------------------------------
insert into XEPLOAI values ('G',N'Giỏi')
insert into XEPLOAI values ('K',N'Khá')
insert into XEPLOAI values ('TB',N'Trung bình')
insert into XEPLOAI values ('Y',N'Yếu')
insert into XEPLOAI values ('Kem',N'Kém')

--------------------------------------------------------------------------------------------------------------
insert into KIEMTRA values ('15p', N'KT 15 phút', 1)
insert into KIEMTRA values ('45p', N'KT 1 tiết', 2)
insert into KIEMTRA values ('CK', N'KT Cuối kỳ', 3)

--------------------------------------------------------------------------------------------------------------
insert into MONHOC values (N'Toán', N'Toán', 2)
insert into MONHOC values (N'Văn', N'Ngữ Văn', 2)
insert into MONHOC values (N'Anh', N'Tiếng Anh', 1)
insert into MONHOC values (N'Hóa', N'Hóa học', 1)
insert into MONHOC values (N'Lý', N'Vật lí', 1)
insert into MONHOC values (N'Địa', N'Địa lí', 1)
insert into MONHOC values (N'Sử', N'Lịch sử', 1)
insert into MONHOC values (N'GDCD', N'Giáo dục công dân', 1)
insert into MONHOC values (N'Sinh', N'Sinh học', 1)
insert into MONHOC values (N'Tin', N'Tin học', 1)

--------------------------------------------------------------------------------------------------------------
insert into NIENKHOA values ('11-12','2011-2012')
insert into NIENKHOA values ('12-13','2012-2013')
insert into NIENKHOA values ('13-14','2013-2014')
insert into NIENKHOA values ('14-15','2014-2015')
insert into NIENKHOA values ('15-16','2015-2016')

--------------------------------------------------------------------------------------------------------------
insert into NGUOIDUNG values ('admin', '12345678', 0)
insert into NGUOIDUNG values ('NV001', '12345678', 0)
insert into NGUOIDUNG values ('GV001', '12345678', 1)
insert into NGUOIDUNG values ('GV002', '12345678', 1)
insert into NGUOIDUNG values ('GV003', '12345678', 1)
insert into NGUOIDUNG values ('GV004', '12345678', 1)
insert into NGUOIDUNG values ('GV005', '12345678', 1)

--------------------------------------------------------------------------------------------------------------
insert into NHANVIEN values ('admin', N'Nguyễn Văn A', '0123456789', 0)
insert into NHANVIEN values ('NV001', N'Nguyễn Văn B', '0123456798', 1)
insert into NHANVIEN values ('NV002', N'Nguyễn Văn C', '0123456987', 1)
insert into NHANVIEN values ('NV003', N'Nguyễn Văn D', '0123456978', 1)
insert into NHANVIEN values ('NV004', N'Nguyễn Văn E', '0123456897', 1)
insert into NHANVIEN values ('NV005', N'Nguyễn Văn F', '0123456879', 1)
insert into NHANVIEN values ('NV006', N'Nguyễn Văn G', '0123457896', 1)

--------------------------------------------------------------------------------------------------------------
insert into KHOI values (10, '13-14', 4, 40, 15, 20)
insert into KHOI values (10, '14-15', 4, 40, 15, 20)
insert into KHOI values (10, '15-16', 4, 40, 15, 20)
insert into KHOI values (10, '11-12', 4, 40, 15, 20)
insert into KHOI values (10, '12-13', 4, 40, 15, 20)
insert into KHOI values (11, '13-14', 3, 40, 15, 20)
insert into KHOI values (11, '14-15', 3, 40, 15, 20)
insert into KHOI values (11, '15-16', 3, 40, 15, 20)
insert into KHOI values (11, '11-12', 3, 40, 15, 20)
insert into KHOI values (11, '12-13', 3, 40, 15, 20)
insert into KHOI values (12, '13-14', 3, 40, 15, 20)
insert into KHOI values (12, '14-15', 3, 40, 15, 20)
insert into KHOI values (12, '15-16', 3, 40, 15, 20)
insert into KHOI values (12, '11-12', 3, 40, 15, 20)
insert into KHOI values (12, '12-13', 3, 40, 15, 20)

--------------------------------------------------------------------------------------------------------------
insert into GIAOVIEN values ('GV001', N'GV001', '1111111111', N'Anh')
insert into GIAOVIEN values ('GV002', N'GV002', '2222222222', N'Toán')
insert into GIAOVIEN values ('GV003', N'GV003', '3333333333', N'Sinh')
insert into GIAOVIEN values ('GV004', N'GV004', '4444444444', N'Văn')
insert into GIAOVIEN values ('GV005', N'GV005', '5555555555', N'Sử')
insert into GIAOVIEN values ('GV006', N'GV006', '6666666666', N'Địa')
insert into GIAOVIEN values ('GV007', N'GV007', '7777777777', N'Tin')
insert into GIAOVIEN values ('GV008', N'GV008', '8888888888', N'GDCD')
insert into GIAOVIEN values ('GV009', N'GV009', '9999999999', N'Lý')
insert into GIAOVIEN values ('GV010', N'GV010', '1010101010', N'Hóa')
insert into GIAOVIEN values ('GV011', N'GV011', '1111111110', N'Anh')
insert into GIAOVIEN values ('GV012', N'GV012', '1212121212', N'Toán')
insert into GIAOVIEN values ('GV013', N'GV013', '1313131313', N'Sinh')
insert into GIAOVIEN values ('GV014', N'GV014', '1414141414', N'Văn')
insert into GIAOVIEN values ('GV015', N'GV015', '1515151515', N'Sử')
insert into GIAOVIEN values ('GV016', N'GV016', '1616161616', N'Địa')
insert into GIAOVIEN values ('GV017', N'GV017', '1717171717', N'Tin')
insert into GIAOVIEN values ('GV018', N'GV018', '1818181818', N'GDCD')
insert into GIAOVIEN values ('GV019', N'GV019', '1919191919', N'Lý')
insert into GIAOVIEN values ('GV020', N'GV020', '2020202020', N'Hóa')
insert into GIAOVIEN values ('GV021', N'GV021', '2121212121', N'Anh')
insert into GIAOVIEN values ('GV022', N'GV022', '2222222220', N'Toán')
insert into GIAOVIEN values ('GV023', N'GV023', '2323232323', N'Sinh')
insert into GIAOVIEN values ('GV024', N'GV024', '2424242424', N'Văn')
insert into GIAOVIEN values ('GV025', N'GV025', '2525252525', N'Sử')
insert into GIAOVIEN values ('GV026', N'GV026', '2626262626', N'Địa')
insert into GIAOVIEN values ('GV027', N'GV027', '2727272727', N'Tin')
insert into GIAOVIEN values ('GV028', N'GV028', '2828282828', N'GDCD')
insert into GIAOVIEN values ('GV029', N'GV029', '2929292929', N'Lý')
insert into GIAOVIEN values ('GV030', N'GV030', '3030303030', N'Hóa')

--------------------------------------------------------------------------------------------------------------
insert into HOCSINH values ('1314001', N'HS001', '01-01-1998', 'Nam', N'Nguyễn Văn Cừ quận 5', null, 10, '13-14')
insert into HOCSINH values ('1314002', N'HS002', '02-02-1998', 'Nam', N'Lý Tự Trọng quận 1', null, 10, '13-14')
insert into HOCSINH values ('1314003', N'HS003', '02-01-1998', N'Nữ', N'Nguyễn Trãi quận 1', null, 10, '13-14')
insert into HOCSINH values ('1314004', N'HS004', '03-01-1998', N'Nữ', N'Thành Thái quận 10', null, 10, '13-14')
insert into HOCSINH values ('1314005', N'HS005', '04-06-1998', 'Nam', N'Lý Thái Tổ quận 10', null, 10, '13-14')
insert into HOCSINH values ('1314006', N'HS006', '05-08-1998', N'Nữ', N'Trường Chinh quận Tân Bình', null, 10, '13-14')
insert into HOCSINH values ('1314007', N'HS007', '11-01-1998', 'Nam', N'Điện Biên Phủ quận 3', null, 10, '13-14')
insert into HOCSINH values ('1314008', N'HS001', '01-01-1998', 'Nam', N'Nguyễn Văn Cừ quận 5', null, 10, '13-14')
insert into HOCSINH values ('1314009', N'HS002', '02-02-1998', N'Nữ', N'Lý Tự Trọng quận 1', null, 10, '13-14')
insert into HOCSINH values ('1314010', N'HS003', '02-01-1998', 'Nam', N'Nguyễn Trãi quận 1', null, 10, '13-14')
insert into HOCSINH values ('1314011', N'HS004', '03-01-1998', N'Nữ', N'Thành Thái quận 10', null, 10, '13-14')
insert into HOCSINH values ('1314012', N'HS005', '04-06-1998', N'Nữ', N'Lý Thái Tổ quận 10', null, 10, '13-14')
insert into HOCSINH values ('1314013', N'HS006', '05-08-1998', 'Nam', N'Trường Chinh quận Tân Bình', null, 10, '13-14')
insert into HOCSINH values ('1314014', N'HS007', '11-01-1998', N'Nữ', N'Điện Biên Phủ quận 3', null, 10, '13-14')
insert into HOCSINH values ('1314015', N'HS001', '01-01-1998', 'Nam', N'Nguyễn Văn Cừ quận 5', null, 10, '13-14')
insert into HOCSINH values ('1314016', N'HS002', '02-02-1998', 'Nam', N'Lý Tự Trọng quận 1', null, 10, '13-14')
insert into HOCSINH values ('1314017', N'HS003', '02-01-1998', N'Nữ', N'Nguyễn Trãi quận 1', null, 10, '13-14')
insert into HOCSINH values ('1314018', N'HS004', '03-01-1998', N'Nữ', N'Thành Thái quận 10', null, 10, '13-14')
insert into HOCSINH values ('1314019', N'HS005', '04-06-1998', 'Nam', N'Lý Thái Tổ quận 10', null, 10, '13-14')
insert into HOCSINH values ('1314020', N'HS006', '05-08-1998', N'Nữ', N'Trường Chinh quận Tân Bình', null, 10, '13-14')
insert into HOCSINH values ('1314021', N'HS007', '11-01-1998', 'Nam', N'Điện Biên Phủ quận 3', null, 10, '13-14')

insert into HOCSINH values ('1516001', N'HS002', '09-01-2000', 'Nam', N'Trường Chinh quận Tân Bình', null, 10, '15-16')

insert into HOCSINH values ('1415001', N'HS003', '01-09-1998', 'Nam', N'Nguyễn Thị Minh Khai quận 3', NULL, 10, '14-15')
insert into HOCSINH values ('1415002', N'HS004', '05-28-1999', N'Nữ', N'Cách Mạng Tháng 8 quận Tân Bình', NULL, 10, '14-15')
insert into HOCSINH values ('1415003', N'HS003', '01-09-1998', 'Nam', N'Nguyễn Thị Minh Khai quận 3', NULL, 10, '14-15')
insert into HOCSINH values ('1415004', N'HS004', '05-28-1999', N'Nữ', N'Cách Mạng Tháng 8 quận Tân Bình', NULL, 10, '14-15')
insert into HOCSINH values ('1415005', N'HS003', '01-09-1998', 'Nam', N'Nguyễn Thị Minh Khai quận 3', NULL, 10, '14-15')
insert into HOCSINH values ('1415006', N'HS004', '05-28-1999', N'Nữ', N'Cách Mạng Tháng 8 quận Tân Bình', NULL, 10, '14-15')
insert into HOCSINH values ('1415007', N'HS003', '01-09-1998', 'Nam', N'Nguyễn Thị Minh Khai quận 3', NULL, 10, '14-15')
insert into HOCSINH values ('1415008', N'HS004', '05-28-1999', N'Nữ', N'Cách Mạng Tháng 8 quận Tân Bình', NULL, 10, '14-15')
insert into HOCSINH values ('1415009', N'HS003', '01-09-1998', 'Nam', N'Nguyễn Thị Minh Khai quận 3', NULL, 10, '14-15')
insert into HOCSINH values ('1415002', N'HS004', '05-28-1999', N'Nữ', N'Cách Mạng Tháng 8 quận Tân Bình', NULL, 10, '14-15')

--------------------------------------------------------------------------------------------------------------
insert into LOP values ('10A01', '11-12',10, '10A01', 5, 'GV010')
insert into LOP values ('10A02', '11-12',10, '10A02', 5, 'GV002')
insert into LOP values ('10A03', '11-12',10, '10A03', 5, 'GV004')
insert into LOP values ('10A04', '11-12',10, '10A04', 5, 'GV006')
insert into LOP values ('11A01', '11-12',11, '11A01', 5, 'GV005')
insert into LOP values ('11A02', '11-12',11, '11A02', 5, 'GV009')
insert into LOP values ('11A03', '11-12',11, '11A03', 5, 'GV007')
insert into LOP values ('12A01', '11-12',12, '12A01', 5, 'GV008')
insert into LOP values ('12A02', '11-12',12, '12A02', 5, 'GV001')

insert into LOP values ('10A01', '12-13',10, '10A01', 5, 'GV008')
insert into LOP values ('10A02', '12-13',10, '10A02', 5, 'GV002')
insert into LOP values ('10A03', '12-13',10, '10A03', 5, 'GV003')
insert into LOP values ('10A04', '12-13',10, '10A04', 5, 'GV004')
insert into LOP values ('11A01', '12-13',11, '11A01', 5, 'GV010')
insert into LOP values ('11A02', '12-13',11, '11A02', 5, 'GV006')
insert into LOP values ('11A03', '12-13',11, '11A03', 5, 'GV007')
insert into LOP values ('12A01', '12-13',12, '12A01', 5, 'GV005')
insert into LOP values ('12A02', '12-13',12, '12A02', 5, 'GV009')

insert into LOP values ('10A01', '13-14',10, '10A01', 5, 'GV007')
insert into LOP values ('10A02', '13-14',10, '10A02', 5, 'GV003')
insert into LOP values ('10A03', '13-14',10, '10A03', 5, 'GV006')
insert into LOP values ('10A04', '13-14',10, '10A04', 5, 'GV004')
insert into LOP values ('11A01', '13-14',11, '11A01', 5, 'GV005')
insert into LOP values ('11A02', '13-14',11, '11A02', 5, 'GV001')
insert into LOP values ('11A03', '13-14',11, '11A03', 5, 'GV002')
insert into LOP values ('12A01', '13-14',12, '12A01', 5, 'GV008')
insert into LOP values ('12A02', '13-14',12, '12A02', 5, 'GV009')

insert into LOP values ('10A01', '13-14',10, '10A01', 7, 'GV001')
insert into LOP values ('10A02', '13-14',10, '10A02', 7, 'GV002')
insert into LOP values ('10A03', '13-14',10, '10A03', 7, 'GV003')
insert into LOP values ('10A04', '13-14',10, '10A04', 7, 'GV004')
insert into LOP values ('11A01', '13-14',11, '11A01', 5, 'GV005')
insert into LOP values ('11A02', '13-14',11, '11A02', 5, 'GV006')
insert into LOP values ('11A03', '13-14',11, '11A03', 5, 'GV007')
insert into LOP values ('12A01', '13-14',12, '12A01', 5, 'GV008')
insert into LOP values ('12A02', '13-14',12, '12A02', 5, 'GV009')

insert into LOP values ('10A01', '14-15',10, '10A01', 5, 'GV001')
insert into LOP values ('10A02', '14-15',10, '10A02', 5, 'GV002')
insert into LOP values ('10A03', '14-15',10, '10A03', 5, 'GV003')
insert into LOP values ('10A04', '14-15',10, '10A04', 5, 'GV004')
insert into LOP values ('11A01', '14-15',11, '11A01', 5, 'GV005')
insert into LOP values ('11A02', '14-15',11, '11A02', 5, 'GV006')
insert into LOP values ('11A03', '14-15',11, '11A03', 5, 'GV007')
insert into LOP values ('12A01', '14-15',12, '12A01', 5, 'GV008')
insert into LOP values ('12A02', '14-15',12, '12A02', 5, 'GV009')

insert into LOP values ('10A01', '15-16',10, '10A01', 5, 'GV001')
insert into LOP values ('10A02', '15-16',10, '10A02', 5, 'GV002')
insert into LOP values ('10A03', '15-16',10, '10A03', 5, 'GV006')
insert into LOP values ('10A04', '15-16',10, '10A04', 5, 'GV003')
insert into LOP values ('11A01', '15-16',11, '11A01', 5, 'GV005')
insert into LOP values ('11A02', '15-16',11, '11A02', 5, 'GV004')
insert into LOP values ('11A03', '15-16',11, '11A03', 5, 'GV009')
insert into LOP values ('12A01', '15-16',12, '12A01', 5, 'GV008')
insert into LOP values ('12A02', '15-16',12, '12A02', 5, 'GV010')

-------------------------------------------------------------------------------------------------------------
insert into SOLANKT values ('15p',N'Toán', 3)
insert into SOLANKT values ('45p',N'Toán', 3)
insert into SOLANKT values ('CK',N'Toán', 1)
insert into SOLANKT values ('15p',N'Văn', 3)
insert into SOLANKT values ('45p',N'Văn', 3)
insert into SOLANKT values ('CK',N'Văn', 1)
insert into SOLANKT values ('15p',N'Anh', 2)
insert into SOLANKT values ('45p',N'Anh', 2)
insert into SOLANKT values ('CK',N'Anh', 1)
insert into SOLANKT values ('15p',N'Lý', 2)
insert into SOLANKT values ('45p',N'Lý', 2)
insert into SOLANKT values ('CK',N'Lý', 1)
insert into SOLANKT values ('15p',N'Hóa', 2)
insert into SOLANKT values ('45p',N'Hóa', 2)
insert into SOLANKT values ('CK',N'Hóa', 1)
insert into SOLANKT values ('15p',N'Sinh', 2)
insert into SOLANKT values ('45p',N'Sinh', 2)
insert into SOLANKT values ('CK',N'Sinh', 1)
insert into SOLANKT values ('15p',N'Sử', 2)
insert into SOLANKT values ('45p',N'Sử', 2)
insert into SOLANKT values ('CK',N'Sử', 1)
insert into SOLANKT values ('15p',N'Địa', 2)
insert into SOLANKT values ('45p',N'Địa', 2)
insert into SOLANKT values ('CK',N'Địa', 1)

-------------------------------------------------------------------------------------------------------------
insert into GV_LOP values ('10A01','GV001','1','13-14')
insert into GV_LOP values ('10A01','GV002','1','13-14')
insert into GV_LOP values ('10A01','GV003','1','13-14')
insert into GV_LOP values ('10A01','GV004','1','13-14')
insert into GV_LOP values ('10A02','GV001','1','13-14')
insert into GV_LOP values ('10A02','GV002','1','13-14')
insert into GV_LOP values ('10A02','GV003','1','13-14')
insert into GV_LOP values ('10A03','GV010','1','13-14')
insert into GV_LOP values ('10A03','GV009','1','13-14')
insert into GV_LOP values ('10A03','GV008','1','13-14')
insert into GV_LOP values ('10A02','GV007','1','13-14')
insert into GV_LOP values ('10A01','GV006','1','13-14')
insert into GV_LOP values ('11A01','GV001','1','13-14')
insert into GV_LOP values ('11A01','GV002','1','13-14')
insert into GV_LOP values ('11A01','GV003','1','13-14')
insert into GV_LOP values ('11A01','GV004','1','13-14')
insert into GV_LOP values ('11A02','GV001','1','13-14')
insert into GV_LOP values ('11A02','GV002','1','13-14')
insert into GV_LOP values ('11A02','GV003','1','13-14')
insert into GV_LOP values ('11A03','GV010','1','13-14')
insert into GV_LOP values ('11A03','GV009','1','13-14')
insert into GV_LOP values ('11A03','GV008','1','13-14')
insert into GV_LOP values ('11A02','GV007','1','13-14')
insert into GV_LOP values ('11A01','GV006','1','13-14')
insert into GV_LOP values ('10A01','GV001','2','13-14')
insert into GV_LOP values ('10A01','GV002','2','13-14')
insert into GV_LOP values ('10A01','GV003','2','13-14')
insert into GV_LOP values ('10A01','GV004','2','13-14')
insert into GV_LOP values ('10A02','GV001','2','13-14')
insert into GV_LOP values ('10A02','GV002','2','13-14')
insert into GV_LOP values ('10A02','GV003','2','13-14')
insert into GV_LOP values ('10A03','GV010','2','13-14')
insert into GV_LOP values ('10A03','GV009','2','13-14')
insert into GV_LOP values ('10A03','GV008','2','13-14')
insert into GV_LOP values ('10A02','GV007','2','13-14')
insert into GV_LOP values ('10A01','GV006','2','13-14')
insert into GV_LOP values ('11A01','GV001','2','13-14')
insert into GV_LOP values ('11A01','GV002','2','13-14')
insert into GV_LOP values ('11A01','GV003','2','13-14')
insert into GV_LOP values ('11A01','GV004','2','13-14')
insert into GV_LOP values ('11A02','GV001','2','13-14')
insert into GV_LOP values ('11A02','GV002','2','13-14')
insert into GV_LOP values ('11A02','GV003','2','13-14')
insert into GV_LOP values ('11A03','GV010','2','13-14')
insert into GV_LOP values ('11A03','GV009','2','13-14')
insert into GV_LOP values ('11A03','GV008','2','13-14')
insert into GV_LOP values ('11A02','GV007','2','13-14')
insert into GV_LOP values ('11A01','GV006','2','13-14')

-------------------------------------------------------------------------------------------------------------
insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', '15p', 1, '1', 7.8)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', '15p', 1, '1', 5.0)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', '15p', 1, '1', 9.3)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', '15p', 1, '1', 6.8)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', '15p', 1, '1', 8.5)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', '15p', 1, '1', 7.5)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', '15p', 1, '1', 6.8)
insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', '15p', 2, '1', 6.3)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', '15p', 2, '1', 8.5)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', '15p', 2, '1', 7.8)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', '15p', 2, '1', 9.3)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', '15p', 2, '1', 10.0)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', '15p', 2, '1', 7.8)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', '15p', 2, '1', 5.3)
insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', '45p', 1, '1', 7.8)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', '45p', 1, '1', 5.0)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', '45p', 1, '1', 9.3)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', '45p', 1, '1', 6.8)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', '45p', 1, '1', 8.5)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', '45p', 1, '1', 7.5)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', '45p', 1, '1', 6.8)
insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', '45p', 2, '1', 6.3)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', '45p', 2, '1', 8.5)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', '45p', 2, '1', 7.8)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', '45p', 2, '1', 9.3)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', '45p', 2, '1', 10.0)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', '45p', 2, '1', 7.8)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', '45p', 2, '1', 5.3)
insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', 'CK', 1, '1', 7.8)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', 'CK', 1, '1', 5.0)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', 'CK', 1, '1', 9.3)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', 'CK', 1, '1', 6.8)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', 'CK', 1, '1', 8.5)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', 'CK', 1, '1', 7.5)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', 'CK', 1, '1', 6.8)

insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', '15p', 1, '2', 7.8)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', '15p', 1, '2', 5.0)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', '15p', 1, '2', 9.3)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', '15p', 1, '2', 6.8)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', '15p', 1, '2', 8.5)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', '15p', 1, '2', 7.5)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', '15p', 1, '2', 6.8)
insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', '15p', 2, '2', 6.3)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', '15p', 2, '2', 8.5)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', '15p', 2, '2', 7.8)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', '15p', 2, '2', 9.3)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', '15p', 2, '2', 10.0)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', '15p', 2, '2', 7.8)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', '15p', 2, '2', 5.3)
insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', '45p', 1, '2', 7.8)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', '45p', 1, '2', 5.0)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', '45p', 1, '2', 9.3)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', '45p', 1, '2', 6.8)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', '45p', 1, '2', 8.5)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', '45p', 1, '2', 7.5)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', '45p', 1, '2', 6.8)
insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', '45p', 2, '2', 6.3)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', '45p', 2, '2', 8.5)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', '45p', 2, '2', 7.8)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', '45p', 2, '2', 9.3)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', '45p', 2, '2', 10.0)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', '45p', 2, '2', 7.8)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', '45p', 2, '2', 5.3)
insert into DIEMKT values ('1314001', '10A01', '13-14', 'Anh', 'CK', 1, '2', 7.8)
insert into DIEMKT values ('1314002', '10A01', '13-14', 'Anh', 'CK', 1, '2', 5.0)
insert into DIEMKT values ('1314003', '10A01', '13-14', 'Anh', 'CK', 1, '2', 9.3)
insert into DIEMKT values ('1314004', '10A01', '13-14', 'Anh', 'CK', 1, '2', 6.8)
insert into DIEMKT values ('1314005', '10A01', '13-14', 'Anh', 'CK', 1, '2', 8.5)
insert into DIEMKT values ('1314006', '10A01', '13-14', 'Anh', 'CK', 1, '2', 7.5)
insert into DIEMKT values ('1314007', '10A01', '13-14', 'Anh', 'CK', 1, '2', 6.8)

-------------------------------------------------------------------------------------------------------------
insert into DIEMTBM values ('1314001', '10A01', '13-14', 'Anh','2', 7.3)
insert into DIEMTBM values ('1314002', '10A01', '13-14', 'Anh','2', 6.2)
insert into DIEMTBM values ('1314003', '10A01', '13-14', 'Anh','2', 8.8)
insert into DIEMTBM values ('1314004', '10A01', '13-14', 'Anh','2', 7.6)
insert into DIEMTBM values ('1314005', '10A01', '13-14', 'Anh','2', 9.0)
insert into DIEMTBM values ('1314006', '10A01', '13-14', 'Anh','2', 7.6)
insert into DIEMTBM values ('1314007', '10A01', '13-14', 'Anh','2', 6.3)

insert into DIEMTBM values ('1314001', '10A01', '13-14', 'Anh','2', 7.3)
insert into DIEMTBM values ('1314002', '10A01', '13-14', 'Anh','2', 6.2)
insert into DIEMTBM values ('1314003', '10A01', '13-14', 'Anh','2', 8.8)
insert into DIEMTBM values ('1314004', '10A01', '13-14', 'Anh','2', 7.6)
insert into DIEMTBM values ('1314005', '10A01', '13-14', 'Anh','2', 9.0)
insert into DIEMTBM values ('1314006', '10A01', '13-14', 'Anh','2', 7.6)
insert into DIEMTBM values ('1314007', '10A01', '13-14', 'Anh','2', 6.3)

-------------------------------------------------------------------------------------------------------------
insert into DIEMTBM_CN values ('1314001', '10A01', '13-14', 'Anh','2', 7.3)
insert into DIEMTBM_CN values ('1314002', '10A01', '13-14', 'Anh','2', 6.2)
insert into DIEMTBM_CN values ('1314003', '10A01', '13-14', 'Anh','2', 8.8)
insert into DIEMTBM_CN values ('1314004', '10A01', '13-14', 'Anh','2', 7.6)
insert into DIEMTBM_CN values ('1314005', '10A01', '13-14', 'Anh','2', 9.0)
insert into DIEMTBM_CN values ('1314006', '10A01', '13-14', 'Anh','2', 7.6)
insert into DIEMTBM_CN values ('1314007', '10A01', '13-14', 'Anh','2', 6.3)

--------------------------------------------------------------------------------------------------------------
insert into KQHT_HK values ('1314001', '10A01','13-14','1', 7.3, 'K', 'T', 'K')
insert into KQHT_HK values ('1314002', '10A01', '13-14', '1', 6.2, 'TB', 'T', 'TB')
insert into KQHT_HK values ('1314003', '10A01', '13-14', '1', 5.1, 'TB', 'T', 'TB')
insert into KQHT_HK values ('1314004', '10A01', '13-14', '1', 9.0, 'G', 'T', 'G')
insert into KQHT_HK values ('1314005', '10A01', '13-14', '1', 8,5, 'G', 'T', 'G')
insert into KQHT_HK values ('1314006', '10A01', '13-14', '1', 6.9, 'K', 'T', 'K')
insert into KQHT_HK values ('1314007', '10A01', '13-14', '1', 7.4, 'K', 'T', 'K')

insert into KQHT_HK values ('1314001', '10A01', '13-14', '2', 7.3, 'K', 'T', 'K')
insert into KQHT_HK values ('1314002', '10A01', '13-14', '2', 6.2, 'TB', 'T', 'TB')
insert into KQHT_HK values ('1314003', '10A01', '13-14', '2', 5.1, 'TB', 'T', 'TB')
insert into KQHT_HK values ('1314004', '10A01', '13-14', '2', 9.0, 'G', 'T', 'G')
insert into KQHT_HK values ('1314005', '10A01', '13-14', '2', 8,5, 'G', 'T', 'G')
insert into KQHT_HK values ('1314006', '10A01', '13-14', '2', 6.9, 'K', 'T', 'K')
insert into KQHT_HK values ('1314007', '10A01', '13-14', '2', 7.4, 'K', 'T', 'K')

-----------------------------------------------------------------------------------------------------------
insert into KQHT_CN values ('1314001', '10A01', '13-14', 7.3, 'K', 'T', 'K', N'Lên lớp')
insert into KQHT_CN values ('1314002', '10A01', '13-14', 6.2, 'TB', 'T', 'TB', N'Lên lớp')
insert into KQHT_CN values ('1314003', '10A01', '13-14', 5.1, 'TB', 'T', 'TB', N'Lên lớp')
insert into KQHT_CN values ('1314004', '10A01', '13-14', 9.0, 'G', 'T', 'G', N'Lên lớp')
insert into KQHT_CN values ('1314005', '10A01', '13-14', 8,5, 'G', 'T', 'G', N'Lên lớp')
insert into KQHT_CN values ('1314006', '10A01', '13-14', 6.9, 'K', 'T', 'K', N'Lên lớp')
insert into KQHT_CN values ('1314007', '10A01', '13-14', 7.4, 'K', 'T', 'K', N'Lên lớp')