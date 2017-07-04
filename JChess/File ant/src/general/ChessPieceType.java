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
public enum ChessPieceType
{
    Pawn(1),//Tốt
    Bishop(2),//Tượng
    Knight(3),//Mã
    Rook(4),//Xe
    Queen(5),//Hậu
    King(6),//Vua
    Null(7);
    
    private int value;
    ChessPieceType(int val) {
        this.value = val;
    }

    public int getValue() {
        return value;
    }

    public void setValue(int value) {
        this.value = value;
    }
    
    public static ChessPieceType getName(int val) {
        for(ChessPieceType type:ChessPieceType.values()) {
            if(type.getValue() == val) {
                return type;
            }
        }
        return null;
    }
}
