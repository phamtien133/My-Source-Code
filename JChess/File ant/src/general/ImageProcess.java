/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package general;

import java.awt.image.BufferedImage;
import java.io.File;
import javax.imageio.ImageIO;

/**
 *
 * @author VuQuang
 */
public class ImageProcess {
    private static final String FOLDER_IMG = "src\\images\\";
    
    /*
        * Hàm này trả về tên của hình ảnh quân cờ chứa trong resource
        * VD: Muon lay quan Mã màu Đen Style Classic
        * Bitmap img=GetChessPieceBitMap(ChessPieceSide.Black,Knight,ChessPieceStyle.Classic);
        * Hàm này lấy hình ảnh trong Resource có tên là "Black_K_1";
        */
    //Con co: xoa style
    public static BufferedImage GetChessPieceBitMap(ChessSide Side, ChessPieceType Type)
    {
        String strImg = "";

        switch (Side)
        {
            case Black: strImg += "Black_";
                break;
            case White: strImg += "White_";
                break;
        }

        switch (Type)
        {
            case Pawn: strImg += "P";
                break;
            case Bishop: strImg += "B";
                break;
            case Knight: strImg += "N";
                break;
            case Rook: strImg += "R";
                break;
            case Queen: strImg += "Q";
                break;
            case King: strImg += "K";
                break;
            case Null:
                return null;
        }

        //strImg += (int)Style;

        //Bitmap img = (Bitmap)Properties.Resources.ResourceManager.GetObject(strImg);
        BufferedImage img = null;
        try {
            File fi = new File(FOLDER_IMG + strImg +".png");
            img = ImageIO.read(fi);
            
        } catch(Exception e) {
            e.printStackTrace();
            return null;
        }

        return img;
    }

}
