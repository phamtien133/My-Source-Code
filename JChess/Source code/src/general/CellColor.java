/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package general;

/**
 *
 * @author VuQuang
 */
public enum CellColor {
    Black(1),//Đen
    White(2),//Trắng
    Blue(3);//Xanh (de highlight)
    
    private int value;
    CellColor(int val) {
        this.value = val;
    }

    public int getValue() {
        return value;
    }

    public void setValue(int value) {
        this.value = value;
    }
    
    public static CellColor getName(int val) {
        for(CellColor type:CellColor.values()) {
            if(type.getValue() == val) {
                return type;
            }
        }
        return null;
    }
}
