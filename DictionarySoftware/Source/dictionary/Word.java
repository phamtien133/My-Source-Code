/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package dictionary;

import java.util.Date;

/**
 *
 * @author Pham Tien
 */
public class Word {
    //Khai báo thuộc tính
    private int _iIndex;        //Thuộc tính vị trí của từ trong mảng
    private String _sWord;      //Thuộc tính từ khóa cần tìm
    private String _sMeaning;   //Thuộc tính nghĩa của từ
    private int _iCount;        //Thuộc tính lưu số lần tra cứu
    private String _sDate;      //Thuộc tính ngày tra cứu
    
    //Các phương thức khởi tạo: mặc định, có tham số truyền vào,
    //khởi tạo sao chép
    Word(){
        _iIndex = -1;
        _sWord = null;
        _sMeaning = null;
        _iCount = 0;
        _sDate = null;        
    }
    Word(int iIndex, String sWord, String sMeaning, int iCount, String sDate){
        _iIndex = iIndex;
        _sWord = sWord;
        _sMeaning = sMeaning;
        _iCount = iCount;
        _sDate = sDate;        
    }
    Word(final Word w){
        _iIndex = w._iIndex;
        _sWord = w._sWord;
        _sMeaning = w._sMeaning;
        _iCount = w._iCount;
        _sDate = w._sDate;
    }
    //Các getter/setter
    public int getIndex(){
        return _iIndex;
    }
    public void setIndex(int iIndex){
        _iIndex = iIndex;
    }
    
    public String getWord(){
        return _sWord;
    }
    public void setWord(String sWord){
        _sWord = sWord;
    }
    
    public String getMeaning(){
        return _sMeaning;
    }
    public void setMeaning(String sMeaning){
        _sMeaning = sMeaning;
    }
    
    public int getCount(){
        return _iCount;
    }
    public void setCount(int iCount){
        _iCount = iCount;
    }
    
    public String getDate(){
        return _sDate;
    }
    public void setDate(String sDate){
        _sDate = sDate;
    }
}
