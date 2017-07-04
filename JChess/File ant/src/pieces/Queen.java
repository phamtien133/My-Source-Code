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
public class Queen {
    static int Side;
    static ArrayList arrMove;

    private static int[][] QueenTable = new int[][]
            {
      //9  8   7   6   5   4   3   2   1  0
       {0, 0,  0,  0,  0,  0,  0,  0,  0, 0}, //0

       {0, -20, -10,-10, -5, -5,-10,-10,-20, 0},
       {0, -10,  0,  0,  0,  0,  0,  0,-10,  0},
       {0, -10,  0,  5,  5,  5,  5,  0,-10,  0},
       {0, -5,   0,  5,  5,  5,  5,  0, -5,  0},
       {0,  0,   0,  5,  5,  5,  5,  0, -5,  0},
       {0, -10,  5,  5,  5,  5,  5,  0,-10,  0},
       {0, -10,  0,  5,  0,  0,  0,  0,-10,  0},
       {0, -20, -10,-10, -5, -5,-10,-10,-20, 0},

       {0, 0,  0,  0,  0,  0,  0,  0,  0, 0}  //9
            };
    public static int GetPositionValue(Point pos, ChessSide eSide)
    {
        if (eSide == ChessSide.Black)
        {
            return QueenTable [pos.y][ pos.x];
        }
        else
        {
            return QueenTable[9 - pos.y][ 9 - pos.x];
        }
    }

    public static ArrayList FindAllPossibleMove(int[][] State, Point pos)//, bool EnPassant)
    {

        arrMove = new ArrayList();

        Side = State[pos.x][ pos.y] % 10;//Chẵn(0) là quân trắng, lẻ(1) là quân đen

        chessloop(State, pos, 0, 1);
        chessloop(State, pos, 1, 0);
        chessloop(State, pos, 0, -1);
        chessloop(State, pos, -1, 0);
        chessloop(State, pos, 1, 1);
        chessloop(State, pos, 1, -1);
        chessloop(State, pos, -1, -1);
        chessloop(State, pos, -1, 1);

        return arrMove;
    }


    static void chessloop(int[][] State, Point pos, int indexx, int indexy)
    {
        int stop = 0;
        int x = pos.x;
        int y = pos.y;
        while (stop == 0)
        {

            int state = State[x += indexx][ y += indexy];
            if (state == 0)
            {
                arrMove.add(new Point(x, y));
            }
            else if (state == -1)
            {
                stop = 1;
            }
            else
            {
                if (state % 10 != Side)
                {
                    arrMove.add(new Point(x, y));
                }
                stop = 1;
            }
        }
    }
}
