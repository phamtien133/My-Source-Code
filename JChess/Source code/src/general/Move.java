/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package general;

import java.awt.Point;
/**
 *
 * @author VuQuang
 */
public class Move {
    
    private Point _CurPos;
    private Point _NewPos;
    private int _Score;
    
    
    
    public Move()
    {
        this._CurPos = new Point ();
        this._NewPos = new Point ();
        this.setScore(0);
    }
    public Move(Point c, Point n)
    {
        this._CurPos = c;
        this._NewPos = n;
        this.setScore(0);
    }

    public Point getCurPos() {
        return _CurPos;
    }

    public void setCurPos(Point _CurPos) {
        this._CurPos = _CurPos;
    }

    public Point getNewPos() {
        return _NewPos;
    }

    public void setNewPos(Point _NewPos) {
        this._NewPos = _NewPos;
    }

    public int getScore() {
        return _Score;
    }

    public void setScore(int _Score) {
        this._Score = _Score;
    }

}
