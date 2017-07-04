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
public enum GameMode
{
    VsComputer(1),
    VsNetWorkHuman(2),
    VsHuman(3);
    private int value;
    GameMode(int val) {
        this.value = val;
    }

    public int getValue() {
        return value;
    }

    public void setValue(int value) {
        this.value = value;
    }
    
    public static GameMode getName(int val) {
        for(GameMode type:GameMode.values()) {
            if(type.getValue() == val) {
                return type;
            }
        }
        return null;
    }
}

