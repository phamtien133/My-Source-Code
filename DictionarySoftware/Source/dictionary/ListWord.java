/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package dictionary;

import java.io.File;
import java.io.IOException;

import java.util.ArrayList;

import javax.xml.parsers.*;
import org.w3c.dom.Element;
import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXParseException;

import java.io.*;
import static java.lang.System.in;
import java.util.Collections;
import java.util.StringTokenizer;
import java.util.Scanner;

/**
 *
 * @author Pham Tien
 */
public class ListWord {

    private ArrayList<Word> _listWord = new ArrayList<Word>();

    //Thêm phần tử word vào list
    public void addWord(Word w) {
        _listWord.add(w);
    }

    //Lấy size của list
    public int getSize() {
        return _listWord.size();
    }

    //Lấy vị trí của phần tử thứ index
    public int getiIndexElement(int index) {
        return _listWord.get(index).getIndex();
    }

    //Lấy từ khóa của phần tử thứ index
    public String getWordElement(int index) {
        return _listWord.get(index).getWord();
    }

    //Lấy nghĩa của phần tử thứ index
    public String getMeaningElement(int index) {
        return _listWord.get(index).getMeaning();
    }

    //Lấy số lần tra cứu của phần tử thứ index
    public int getCountElement(int index) {
        return _listWord.get(index).getCount();
    }

    //Lấy ngày tra cứu của phần tử thứ index
    public String getDateElement(int index) {
        return _listWord.get(index).getDate();
    }
    //Gán số lần tra cứu
//    public void setCountElement(int iCount, int iIndex){
//        _listWord.get(iIndex).setCount( );
//    }
    //Lấy vị trí của phần tử
    public int getIndexElement(String sWord) {
        for (int i = 0; i < _listWord.size(); ++i) {
            if (_listWord.get(i).getWord().equals(sWord)) {
                return i;
            }
        }
        return -1;
    }

    //Tìm phần tử có từ khóa bằng sWord
    public Word findWord(String sWord) {
        for (Word Temp : _listWord) {
            if (Temp.getWord().equals(sWord)) {
                return Temp;
            }
        }
        return null;
    }
    //Tìm phần tử có từ khóa bằng sWord
    public Word findWordByIndex(int iIndex) {
        return _listWord.get(iIndex);
    }
    //Đọc file xml
    public void readXML(String fileName) {
        try {
            DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
            DocumentBuilder builder = factory.newDocumentBuilder();

            File file = new File(fileName);

            Document document = builder.parse(file);
            Element root = document.getDocumentElement();

            NodeList list = root.getElementsByTagName("record");
            for (int i = 0; i < list.getLength(); i++) {
                Word w = new Word();
                Element eWord = (Element) list.item(i);
                String sWord = eWord.getElementsByTagName("word").item(0).getTextContent();
                w.setWord(sWord);
                String sMeaning = eWord.getElementsByTagName("meaning").item(0).getTextContent();
                w.setMeaning(sMeaning);
                _listWord.add(w);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    //Đọc file: xử lý cho trường hợp từ yêu thích và thống kê (isStatistic != 0)
    public void readFile(String fileName, int isStatistic)
            throws IOException {
        _listWord.clear();
        FileInputStream file = new FileInputStream(fileName);
        DataInputStream buff = new DataInputStream(file);
        StringBuilder str = new StringBuilder();
        int content;
        while ((content = buff.read()) != -1) {
            str.append((char) content);
        }
        StringTokenizer strtok = new StringTokenizer(str.toString(), "_");
        while (strtok.hasMoreTokens()) {
            if (isStatistic == 0) {
                Word w = new Word();
                w.setIndex(Integer.parseInt(strtok.nextToken()));
                _listWord.add(w);
            } else {
                Word w = new Word();
                w.setIndex(Integer.parseInt(strtok.nextToken()));
                w.setCount(Integer.parseInt(strtok.nextToken()));
                w.setDate(strtok.nextToken());
                _listWord.add(w);
            }
        }
        buff.close();
        file.close();
    }

    //Ghi file: xử lý cho trường hợp từ yêu thích và thống kê (isStatistic != 0)
    public void writeFile(String fileName, int isStatistic) throws IOException {
        FileOutputStream file = new FileOutputStream(fileName);
        DataOutputStream buff = new DataOutputStream(file);
        file.flush();
        if (isStatistic == 0) {
            for (Word w : _listWord) {
                String strIndex = String.format("%d_", w.getIndex());
                byte[] arrByte = strIndex.getBytes();
                buff.write(arrByte, 0, arrByte.length);
                buff.flush();
            }

        } else {
            for (Word w : _listWord) {
                String strIndex = String.format("%d_%d_%s_",
                        w.getIndex(),
                        w.getCount(),
                        w.getDate());
                byte[] arrByte = strIndex.getBytes();
                buff.write(arrByte, 0, arrByte.length);
                buff.flush();
            }

        }
        buff.close();
        file.close();
    }

}
