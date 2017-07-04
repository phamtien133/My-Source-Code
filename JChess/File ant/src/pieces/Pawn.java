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
import java.awt.image.BufferedImage;
/**
 *
 * @author VuQuang
 */
public class Pawn {
    //***Hàm trả về tất cả các nước đi có thể của 1 quân tốt, kể cả nước ăn quân***
    //EnPassant: Bắt Tốt Qua Đường
    private static int[][] PawnTable = new int[][]
            {
      //9  8   7   6   5   4   3   2   1  0
       {0, 0,  0,  0,  0,  0,  0,  0,  0, 0}, //0
       {0, 0,  0,  0,  0,  0,  0,  0,  0, 0}, //1
       {0, 50, 50, 50, 50, 50, 50, 50, 50, 0},//2
       {0, 10, 10, 20, 30, 30, 20, 10, 10, 0},//3
       {0, 5,  5, 10, 27, 27, 10,  5,  5, 0}, //4
       {0,-5, -5,-10, 25, 25, -5, -5,  0, 0}, //5
       {0, 5, -5,-10,  0,  0,-10, -5,  5, 0}, //6
       {0, 5, 10, 10,-25,-25, 10, 10,  5, 0}, //7
       {0, 0,  0,  0,  0,  0,  0,  0,  0, 0}, //8
       {0, 0,  0,  0,  0,  0,  0,  0,  0, 0}  //9
            };
    public static int GetPositionValue(Point pos, ChessSide eSide)
    {
        int value = 0;
        if (eSide == ChessSide.Black)
        {

            value = PawnTable[pos.y][ pos.x];
            //Tốt cánh xe bị trừ 15% giá trị
            if (pos.x == 8 || pos.x == 1)
                value -= 15;
        }
        else
        {

            value = PawnTable[9 - pos.y][ 9 - pos.x];
            if (pos.x == 8 || pos.x == 1)
                value -= 15;
        }
        return value;
    }


    public static ArrayList FindAllPossibleMove(int[][] State, Point pos)//, bool EnPassant)
    {
        //Từ vị trí ban đầu quân tốt có thể di chuyển về phía trước 1 hoặc 2 ô các vị trí còn lại : 1 ô
        //Nước di chuyển 2 ô có thể kích hoạt trạng thái "Bắt Tốt Qua Đường(Enpassant)"
        //Trạng thái Enpassant cỉ có hiệu lực trong 1 Nước cờ
        //(nếu đối phương ko ăn quân trong lượt đó thì trạng thái Enpassant sẽ mất hiệu lực)
        ArrayList arrMove = new ArrayList();
        /*
         * Nếu ô phía trước không bị cản thì có thể di chuyển
         */
        int Side = State[pos.x][pos.y] % 10;//Chẵn(2) là quân trắng, lẻ(1) là quân đen

        if (Side == 2)//Quân Trắng
        {
            if (State[pos.x][pos.y + 1] == 0)
            {
                arrMove.add(new Point(pos.x, pos.y + 1));

                if (pos.y == 2 && State[pos.x][ pos.y + 2] == 0)//Vi tri ban dau
                    arrMove.add(new Point(pos.x, pos.y + 2));
                //Phong Cấp
                if (pos.y == 7)
                {
                    arrMove.add(new Point(pos.x, pos.y + 1));
                    arrMove.add(new Point(pos.x, pos.y + 1));
                    arrMove.add(new Point(pos.x, pos.y + 1));
                }
            }
            //Ăn quân
            //Nếu đường chéo ở 2 ô trước mặt là quân đen
            if (State[pos.x - 1][ pos.y + 1] % 10 == 1)
            {
                arrMove.add(new Point(pos.x - 1, pos.y + 1));
                if (pos.y == 7)
                {
                    arrMove.add(new Point(pos.x - 1, pos.y + 1));
                    arrMove.add(new Point(pos.x - 1, pos.y + 1));
                    arrMove.add(new Point(pos.x - 1, pos.y + 1));
                }
            }
            if (State[pos.x + 1][ pos.y + 1] % 10 == 1)
            {
                arrMove.add(new Point(pos.x + 1, pos.y + 1));
                if (pos.y == 7)
                {
                    arrMove.add(new Point(pos.x + 1, pos.y + 1));
                    arrMove.add(new Point(pos.x + 1, pos.y + 1));
                    arrMove.add(new Point(pos.x + 1, pos.y + 1));
                }
            }


        }
        else if (Side == 1)
        {
            if (State[pos.x][ pos.y - 1] == 0)
            {
                arrMove.add(new Point(pos.x, pos.y - 1));

                if (pos.y == 7 && State[pos.x][ pos.y - 2] == 0)//Vi tri ban dau
                    arrMove.add(new Point(pos.x, pos.y - 2));

                if (pos.y == 2)
                {
                    arrMove.add(new Point(pos.x, pos.y - 1));
                    arrMove.add(new Point(pos.x, pos.y - 1));
                    arrMove.add(new Point(pos.x, pos.y - 1));
                }

            }
            //Ăn quân
            //Nếu đường chéo ở 2 ô trước mặt là quân Trắng
            if (State[pos.x - 1][pos.y - 1] % 10 == 2)
            {
                arrMove.add(new Point(pos.x - 1, pos.y - 1));
                if (pos.y == 2)
                {
                    arrMove.add(new Point(pos.x - 1, pos.y - 1));
                    arrMove.add(new Point(pos.x - 1, pos.y - 1));
                    arrMove.add(new Point(pos.x - 1, pos.y - 1));
                }
            }

            if (State[pos.x + 1][ pos.y - 1] % 10 == 2)
            {
                arrMove.add(new Point(pos.x + 1, pos.y - 1));
                if (pos.y == 2)
                {
                    arrMove.add(new Point(pos.x + 1, pos.y - 1));
                    arrMove.add(new Point(pos.x + 1, pos.y - 1));
                    arrMove.add(new Point(pos.x + 1, pos.y - 1));
                }
            }


        }

        Point pt = ChessBoard._EnPassantPoint;
        if (pt != new Point())//Nếu có tọa đọ bắt tốt
        {
            if (pos.y == 4 && Side == 1)//Nếu là quân tốt đen
            {
                if (new Point(pos.x - 1, 3) == pt)//Đường chéo phải
                {
                    arrMove.add(ChessBoard._EnPassantPoint);
                }

                if (new Point(pos.x + 1, 3) == pt)//Đường chéo trái
                {
                    arrMove.add(ChessBoard._EnPassantPoint);
                }
            }

            if (pos.y == 5 && Side == 2)
            {
                if (new Point(pos.x - 1, 6) == pt)
                {
                    arrMove.add(ChessBoard._EnPassantPoint);
                }

                if (new Point(pos.x + 1, 6) == pt)
                {
                    arrMove.add(ChessBoard._EnPassantPoint);
                }
            }
        }
        return arrMove;

    }
    public static Point GetEnPassantPoint(int[][] State, Point pos)
    {
        if (pos.y == 4)
            return new Point(pos.x, pos.y - 1);
        if (pos.y == 5)
            return new Point(pos.x, pos.y + 1);
        return new Point();
    }

}
