/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package pieces;

import java.awt.Point;
import java.util.ArrayList;

import general.ChessSide;
/**
 *
 * @author VuQuang
 */
public class Knight {
    private static int[][] KnightTable = new int[][]
            {
      //9  8   7   6   5   4   3   2   1  0
       {0, 0,  0,  0,  0,  0,  0,  0,  0, 0}, //0
       {0,-50,-40,-30,-30,-30,-30,-40,-50,0},
       {0,-40,-20,  0,  0,  0,  0,-20,-40,0},
       {0,-30,  0, 10, 15, 15, 10,  0,-30,0},
       {0,-30,  5, 15, 20, 20, 15,  5,-30,0},
       {0,-30,  0, 15, 20, 20, 15,  0,-30,0},
       {0,-30,  5, 10, 15, 15, 10,  5,-30,0},
       {0,-40,-20,  0,  5,  5,  0,-20,-40,0},
       {0,-50,-40,-20,-30,-30,-20,-40,-50,0},
       {0, 0,  0,  0,  0,  0,  0,  0,  0, 0}  //9
            };

    public static int GetPositionValue(Point pos, ChessSide eSide)
    {
        if (eSide == ChessSide.Black)
        {
            return KnightTable[pos.y][ pos.x];
        }
        else
        {
            return KnightTable[9 - pos.y][ 9 - pos.x];
        }
    }

    public static ArrayList FindAllPossibleMove(int[][] State, Point pos)
    {
        ArrayList arrMove = new ArrayList();
        int Side = State[pos.x][ pos.y] %10;//Chẵn(2) là quân trắng, lẻ(1) là quân đen
        /*
         *       1 | 2
         *     3   |   4
         *     ----N----
         *     5   |   6
         *       7 | 8
         * Điều Kiện:
         * Vị Trí 1: X>=2 && Y<=6  (Y+2,X-1)
         * Vị Trí 2: X<=7 && Y<=6 (Y+2,X+1)
         * Vị Trí 3: X>=3 && Y<=7 (Y+1,X-2)
         * Vị Trí 4: X<=6 && Y<=7  (Y+1,X+2)
         * Vị Trí 5: X>=3 && Y>=2   (Y-1,X-2)
         * Vị Trí 6: X<=6 && Y>=2  (Y-1,X+2)
         * Vị Trí 7: X>=2 && Y>=3   (Y-2,X-1)
         * Vị Trí 8: X<=7 && Y>=3  (Y-2,X+1)
         */


        //Vị Trí 1
        if (pos.x >= 2 && pos.y <= 6)
        {
            if (State[pos.x - 1][ pos.y + 2] % 10 != Side || State[pos.x - 1][ pos.y + 2] == 0)
                arrMove.add(new Point(pos.x - 1, pos.y + 2));
        }
        //Vị Trí 2
        if (pos.x <= 7 && pos.y <= 6)
        {
            if (State[pos.x + 1][ pos.y + 2] % 10 != Side || State[pos.x + 1][ pos.y + 2] == 0)
                arrMove.add(new Point(pos.x + 1, pos.y + 2));
        }
        //Vị Trí 3
        if (pos.x >= 3 && pos.y <= 7)
        {
            if (State[pos.x - 2][ pos.y + 1] % 10 != Side || State[pos.x - 2][ pos.y + 1] == 0)
                arrMove.add(new Point(pos.x - 2, pos.y + 1));
        }
        //Vị Trí 4
        if (pos.x <= 6 && pos.y <= 7)
        {
            if (State[pos.x + 2][ pos.y + 1] % 10 != Side || State[pos.x + 2][ pos.y + 1] == 0)
                arrMove.add(new Point(pos.x + 2, pos.y + 1));
        }
        //Vị Trí 5
        if (pos.x >= 3 && pos.y >= 2)
        {
            if (State[pos.x - 2][ pos.y - 1] % 10 != Side || State[pos.x - 2][ pos.y - 1] == 0)
                arrMove.add(new Point(pos.x - 2, pos.y - 1));
        }
        //Vị Trí 6
        if (pos.x <= 6 && pos.y >= 2)
        {
            if (State[pos.x + 2][ pos.y - 1] % 10 != Side || State[pos.x + 2][ pos.y - 1] == 0)
                arrMove.add(new Point(pos.x + 2, pos.y - 1));
        }
        //Vị Trí 7
        if (pos.x >= 2 && pos.y >= 3)
        {
            if (State[pos.x - 1][ pos.y - 2] % 10 != Side || State[pos.x - 1][ pos.y - 2] == 0)
                arrMove.add(new Point(pos.x - 1, pos.y - 2));
        }
        //Vị Trí 8
        if (pos.x <= 7 && pos.y >= 3)
        {
            if (State[pos.x + 1][ pos.y - 2] % 10 != Side || State[pos.x + 1][ pos.y - 2] == 0)
                arrMove.add(new Point(pos.x + 1, pos.y - 2));
        }
        return arrMove;
    }
}
