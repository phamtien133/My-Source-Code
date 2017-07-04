/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package pieces;

import general.ChessBoard;
import general.ChessPieceType;
import java.awt.Point;
import java.util.ArrayList;

import general.ChessSide;
import java.lang.reflect.Array;
import java.util.Arrays;
/**
 *
 * @author VuQuang
 */
public class King {
    private static int[][] KingTable = new int[][]
            {
      //9  8   7   6   5   4   3   2   1  0
       {0,   0,  0,  0,  0,  0,  0,  0,  0,   0}, //0

       {0,  -30, -40, -40, -50, -50, -40, -40, -30  ,0},
       {0,  -30, -40, -40, -50, -50, -40, -40, -30  ,0},
       {0,  -30, -40, -40, -50, -50, -40, -40, -30  ,0},
       {0,  -30, -40, -40, -50, -50, -40, -40, -30  ,0},
       {0,  -20, -30, -30, -40, -40, -30, -30, -20  ,0},
       {0,  -10, -20, -20, -20, -20, -20, -20, -10  ,0},
       {0,   20,  20, -5,  -5,  -5,  -5,   20,  20  ,0},
       {0,   20,  30, 10,  0,   0,  10, 30,  20  ,0},

       {0,   0,  0,  0,  0,  0,  0,  0,  0,   0}  //9
            };

    private static int[][] KingTableEndGame = new int[][]
            {
      //9  8   7   6   5   4   3   2   1  0
       {0,   0,  0,  0,  0,  0,  0,  0,  0,   0}, //0
       {0,  -50,-40,-30,-20,-20,-30,-40,-50,  0},
       {0,  -30,-20,-10,  0,  0,-10,-20,-30,  0},
       {0,  -30,-10, 20, 30, 30, 20,-10,-30,  0},
       {0,  -30,-10, 30, 40, 40, 30,-10,-30,  0},
       {0,  -30,-10, 30, 40, 40, 30,-10,-30,  0},
       {0,  -30,-10, 20, 30, 30, 20,-10,-30,  0},
       {0,  -30,-30,  0,  0,  0,  0,-30,-30,  0},
       {0,  -50,-30,-30,-30,-30,-30,-30,-50,  0},

       {0,   0,  0,  0,  0,  0,  0,  0,  0,   0}  //9
            };

    public static int GetPositionValue(Point pos, ChessSide eSide, Boolean IsEndGame)
    {
        if (IsEndGame == false)
        {
            if (eSide == ChessSide.Black)
            {
                return KingTable[pos.y][pos.x];
            }
            else
            {
                return KingTable[9 - pos.y][9 - pos.x];
            }
        }
        else
        {
            if (eSide == ChessSide.Black)
            {
                return KingTableEndGame[pos.y][ pos.x];
            }
            else
            {
                return KingTableEndGame[9 - pos.y][ 9 - pos.x];
            }
        }
    }



    public static ArrayList FindAllPossibleMove(int[][] State, Point pos)
    {
        ArrayList arrMove = new ArrayList();
        int Side = State[pos.x][ pos.y] % 10;//Chẵn(0) là quân trắng, lẻ(1) là quân đen

        if (State[pos.x + 1][ pos.y] >= 0 && (State[pos.x + 1][ pos.y] == 0 || State[pos.x + 1][ pos.y] % 10 != Side))
            arrMove.add(new Point(pos.x + 1, pos.y));//phải

        if (State[pos.x - 1][ pos.y] >= 0 && (State[pos.x - 1][ pos.y] == 0 || State[pos.x - 1][ pos.y] % 10 != Side))
            arrMove.add(new Point(pos.x - 1, pos.y));//trái

        if (State[pos.x][ pos.y + 1] >= 0 && (State[pos.x][ pos.y + 1] == 0 || State[pos.x][ pos.y + 1] % 10 != Side))
            arrMove.add(new Point(pos.x, pos.y + 1));//trên

        if (State[pos.x][ pos.y - 1] >= 0 && (State[pos.x][ pos.y - 1] == 0 || State[pos.x][ pos.y - 1] % 10 != Side))
            arrMove.add(new Point(pos.x,pos.y - 1));//dưới

        if (State[pos.x + 1][ pos.y + 1] >= 0 && (State[pos.x + 1][ pos.y + 1] == 0 || State[pos.x + 1][ pos.y + 1] % 10 != Side))
            arrMove.add(new Point(pos.x + 1, pos.y + 1));//trên phải

        if (State[pos.x - 1][ pos.y + 1] >= 0 && (State[pos.x - 1][ pos.y + 1] == 0 || State[pos.x - 1][ pos.y + 1] % 10 != Side))
            arrMove.add(new Point(pos.x - 1, pos.y + 1));//trên trái

        if (State[pos.x + 1][ pos.y - 1] >= 0 && (State[pos.x + 1][ pos.y - 1] == 0 || State[pos.x + 1][ pos.y - 1] % 10 != Side))
            arrMove.add(new Point(pos.x + 1, pos.y - 1));//dưới phải

        if (State[pos.x - 1][ pos.y - 1] >= 0 && (State[pos.x - 1][ pos.y - 1] == 0 || State[pos.x - 1][ pos.y - 1] % 10 != Side))
            arrMove.add(new Point(pos.x - 1, pos.y - 1));//dưới trái


        return arrMove;
    }

    public static void addCastlingPoint(int[][] State, ChessSide eSide, ArrayList arrMoves)
    {

        if (IsChecked(State, eSide) == false)
        {
            if (eSide == ChessSide.White)
            {
                if (ChessBoard.KINGsideCastling == true)//Nhập thành gần
                {
                    int x = 5;
                    while (State[++x][ 1] == 0) ;

                    if (x == 8)//Không có khoảng trống giữa vua và xe
                    {
                        if (IsChecked(State, eSide, new Point(7, 1)) == false && IsChecked(State, eSide, new Point(6, 1)) == false)
                        {
                            arrMoves.add(new Point(7, 1));
                        }
                    }

                }
                if (ChessBoard.QUEENsideCastling == true)//Nhập thành xa
                {
                    int x = 5;
                    while (State[--x][ 1] == 0) ;

                    if (x == 1)//Không có khoảng trống giữa vua và xe
                    {
                        if (IsChecked(State, eSide, new Point(3, 1)) == false && IsChecked(State, eSide, new Point(4, 1)) == false)
                        {
                            arrMoves.add(new Point(3, 1));
                        }
                    }

                }
            }
            else
            {
                if (ChessBoard.kingsideCastling == true)//Nhập thành gần
                {
                    int x = 5;
                    while (State[++x][ 8] == 0) ;

                    if (x == 8)//Không có khoảng trống giữa vua và xe
                    {
                        if (IsChecked(State, eSide, new Point(7, 8)) == false && IsChecked(State, eSide, new Point(6, 8)) == false)
                        {
                            arrMoves.add(new Point(7, 8));
                        }
                    }

                }
                if (ChessBoard.queensideCastling == true)//Nhập thành xa
                {
                    int x = 5;
                    while (State[--x][ 8] == 0) ;

                    if (x == 1)//Không có khoảng trống giữa vua và xe
                    {
                        if (IsChecked(State, eSide, new Point(3, 8)) == false && IsChecked(State, eSide, new Point(4, 8)) == false)
                        {
                            arrMoves.add(new Point(3, 8));
                        }
                    }
                }
            }
        }
    }

    //Hàm kiểm tra quân vua của 1 phe có đang bị chiếu hay không
    public static Boolean IsChecked(int[][] arrState, ChessSide eSide)
    {

        int[][] State = new int[10][ 10];
        //Array.Copy(arrState, State, arrState.Length);//copy mảng    
           
        for(int i = 0; i< arrState.length; i++){
            for (int j = 0; j < arrState.length; j++){
                State[i][j] = arrState[i][j];
            }
        }
        
        Point pKingPos = FindKingPosition(State, eSide);//Tìm vị trí quân vua đang kiểm tra

        int intSide = eSide.getValue();//Phe của quân đang kiểm tra           

        for (int y = 1; y <= 8; y++)
            for (int x = 1; x <= 8; x++)
            {

                if (State[x][ y] > 0 && State[x][ y] % 10 != intSide)//Nếu là quân khác phe
                {

                    ArrayList arrMove = new ArrayList();
                    ChessPieceType eType = ChessPieceType.getName(State[x][ y] / 10);

                    if (eType == ChessPieceType.Bishop)
                    {
                        arrMove = Bishop.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.King)
                    {
                        arrMove = King.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.Knight)
                    {
                        arrMove = Knight.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.Pawn)
                    {
                        arrMove = Pawn.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.Queen)
                    {
                        arrMove = Queen.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.Rook)
                    {
                        arrMove = Rook.FindAllPossibleMove(State, new Point(x, y));
                    }
                    for (Object p : arrMove)
                    {
                        if (p == pKingPos)
                            return true;
                    }
                }
            }
        return false;

    }
    //Hàm Kiểm Tra 1 ô có bị khống chế hay không
    public static Boolean IsChecked(int[][] arrState, ChessSide eSide, Point pos)
    {

        int[][] State = new int[10][10];
        //Array.Copy(arrState, State, arrState.Length);//copy mảng    
           
        for(int i = 0; i< arrState.length; i++){
            for (int j = 0; j < arrState.length; j++){
                State[i][j] = arrState[i][j];
            }
        }
        int intSide = eSide.getValue();//Phe của quân đang kiểm tra           

        for (int y = 1; y <= 8; y++)
            for (int x = 1; x <= 8; x++)
            {

                if (State[x][ y] > 0 && State[x][ y] % 10 != intSide)//Nếu là quân khác phe
                {

                    ArrayList arrMove = new ArrayList();
                    ChessPieceType eType = ChessPieceType.getName(State[x][ y] / 10);

                    if (eType == ChessPieceType.Bishop)
                    {
                        arrMove = Bishop.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.King)
                    {
                        arrMove = King.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.Knight)
                    {
                        arrMove = Knight.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.Pawn)
                    {
                        arrMove = Pawn.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.Queen)
                    {
                        arrMove = Queen.FindAllPossibleMove(State, new Point(x, y));
                    }
                    if (eType == ChessPieceType.Rook)
                    {
                        arrMove = Rook.FindAllPossibleMove(State, new Point(x, y));
                    }
                    for (Object p : arrMove)
                    {
                        if (p == pos)
                            return true;
                    }
                }
            }
        return false;
    }

    public static Point FindKingPosition(int[][] arrState, ChessSide eSide)
    {
        int intKing = 60 + eSide.getValue();
        for (int y = 1; y <= 8; y++)
            for (int x = 1; x <= 8; x++)
            {
                if (arrState[x][ y] == intKing)
                {
                    return new Point(x, y);
                }
            }
        return new Point();
    }  
}
