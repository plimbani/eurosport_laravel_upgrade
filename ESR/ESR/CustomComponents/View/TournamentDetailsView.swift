//
//  TournamentDetailsView.swift
//  ESR
//
//  Created by Pratik Patel on 15/10/18.
//

import UIKit

class TournamentDetailsView: UIView {

    @IBOutlet var stackView: UIStackView!
    @IBOutlet var contactNoView: UIView!
    @IBOutlet var nameView: UIView!
    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var lblName: UILabel!
    @IBOutlet var lblContact: UILabel!
    
    @IBOutlet var btnContact: UIButton!
    var nameString = NULL_STRING
    var contactString = NULL_STRING
    
    override func awakeFromNib() {
        lblTitle.text = String.localize(key: "alert_tournament_details")
        addAttributedString()
    }
    
    internal func show(_ animated: Bool = false) {
        if animated {
            UIView.beginAnimations(nil, context: nil)
            UIView.setAnimationDuration(0.55)
            self.alpha = 1.0
            UIView.commitAnimations()
        } else {
            self.alpha = 1.0
        }
    }
    
    internal func hide(_ animated: Bool = false) {
        if animated {
            UIView.animate(withDuration: 0.5, delay: 0.1, options: .curveEaseOut, animations: {
                UIView.beginAnimations(nil, context: nil)
                UIView.setAnimationDuration(0.55)
                self.alpha = 0.0
                UIView.commitAnimations()
            }, completion: { (finished: Bool) in
            })
        } else {
            self.alpha = 0.0
        }
    }
    
    func reload() {
        addAttributedString()
        
        if contactString == NULL_STRING {
            //stackView.removeArrangedSubview(contactNoView)
            //contactNoView.removeFromSuperview()
            contactNoView.isHidden = true
        } else {
            contactNoView.isHidden = false
        }
        
        lblName.text = "Name: " + nameString
        layoutIfNeeded()
    }
    
    func addAttributedString(){
        let font1 = UIFont(name: Font.HELVETICA_REGULAR, size: 15)
        let titleAttributes: [NSAttributedString.Key : Any] = [
            NSAttributedString.Key.underlineStyle : NSUnderlineStyle.single.rawValue,
            NSAttributedString.Key.font : font1,
            ]
        
        let range = (contactString as NSString).range(of: contactString)
        let attributedString = NSMutableAttributedString(string: contactString, attributes: titleAttributes)
        attributedString.addAttribute(NSAttributedString.Key.foregroundColor, value: UIColor.tournamentDetailYellow , range: range)
        
        btnContact.setAttributedTitle(attributedString, for: .normal)
        btnContact.titleLabel?.numberOfLines = 0
        btnContact.titleLabel?.textColor = UIColor.tournamentDetailYellow
    }
    
    @IBAction func btnContactPressed(_ sender: UIButton) {
        if let url = URL(string: "tel://\(btnContact.titleLabel!.text!)"), UIApplication.shared.canOpenURL(url) {
            if #available(iOS 10, *) {
                UIApplication.shared.open(url)
            } else {
                UIApplication.shared.openURL(url)
            }
        }
    }
    
    @IBAction func btnClosePressed(_ sender: UIButton) {
        hide()
    }
}
