//
//  FollowTournamentCell.swift
//  ESR
//
//  Created by Pratik Patel on 19/03/19.
//

import UIKit
import SDWebImage

class FollowTournamentCell: UITableViewCell {

    @IBOutlet var imgView: UIImageView!
    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var btnFav: UIButton!
    @IBOutlet var btnRightArrow: UIButton!
    
    @IBOutlet var widthConstraintBtnFav: NSLayoutConstraint!
    
    var dic: Tournament!
    
    override func awakeFromNib() {
        super.awakeFromNib()
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func hideBtnFav(_ flag: Bool = true) {
        
        if !flag {
            widthConstraintBtnFav.constant = 30
            btnFav.imageEdgeInsets = UIEdgeInsets(top: 2, left: 2, bottom: 2, right: 2)
            return
        }
        
        widthConstraintBtnFav.constant = 0
        btnFav.imageEdgeInsets = UIEdgeInsets(top: 0, left: 0, bottom: 0, right: 0)
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
    
    func reloadCell() {
        lblTitle.text = dic.name
        
        if !dic.tournamentLogo.isEmpty {
            imgView.sd_setImage(with: URL(string: dic.tournamentLogo), completed: nil)
        } else {
            imgView.image = UIImage.init(named: "globe")
        }
        
        dic.isFavourite ? hideBtnFav(false) : hideBtnFav()
    }
}
