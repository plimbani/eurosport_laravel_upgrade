//
//  FavouriteTournamentCell.swift
//  ESR
//
//  Created by Pratik Patel on 23/08/18.
//

import UIKit

protocol FavouriteTournamentCellDelegate {
    func favTournamentCellFavBtnPressed(_ indexPath: IndexPath)
    func favTournamentCellSelectDefaultBtnPressed(_ indexPath: IndexPath)
}

class FavouriteTournamentCell: UITableViewCell {

    @IBOutlet var lblTournamentName: UILabel!
    @IBOutlet var lblDate: UILabel!
    @IBOutlet var btnFavourite: UIButton!
    @IBOutlet var btnSelectDefaultTournament: UIButton!
    
    var record = Tournament()
    var delegate: FavouriteTournamentCellDelegate?
    var indexPath: IndexPath!
    
    override func awakeFromNib() {
        super.awakeFromNib()
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func reloadCell() {
        lblTournamentName.text = record.name
        lblDate.text = record.startDate + " - " + record.endDate
        
        btnFavourite.setImage(UIImage(named: "fav")?.withRenderingMode(.alwaysTemplate), for: .normal)
        btnSelectDefaultTournament.setImage(UIImage(named: "selected_default_tournament")?.withRenderingMode(.alwaysTemplate), for: .normal)
        
        if record.isFavourite {
            btnFavourite.tintColor = UIColor.AppColor()
        } else {
            btnFavourite.tintColor = UIColor.favUnfav
        }
        
        if record.isDefault == 1 {
            btnSelectDefaultTournament.tintColor = UIColor.favDefaultSelected
        } else {
            btnSelectDefaultTournament.tintColor = UIColor.favUnfav
        }
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
    
    @IBAction func onFavouriteBtnPressed(_ sender: UIButton) {
        delegate?.favTournamentCellFavBtnPressed(indexPath)
    }
    
    @IBAction func onSelectDefaultBtnPressed(_ sender: UIButton) {
        delegate?.favTournamentCellSelectDefaultBtnPressed(indexPath)
    }
}
