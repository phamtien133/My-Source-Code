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
public enum ChessSide
{
    Black(1),//Đen
    White(2);//Trắng
    
    private int value;
    ChessSide(int val) {
        this.value = val;
    }

    public int getValue() {
        return value;
    }

    public void setValue(int value) {
        this.value = value;
    }
    
    public static ChessSide getName(int val) {
        for(ChessSide type:ChessSide.values()) {
            if(type.getValue() == val) {
                return type;
            }
        }
        return null;
    }
}
