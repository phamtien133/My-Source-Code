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
	KETQUA nvarchar(20),
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
	CMDN char(10) unique,
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
