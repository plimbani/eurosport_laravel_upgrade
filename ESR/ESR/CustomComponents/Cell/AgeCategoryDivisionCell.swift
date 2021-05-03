//
//  AgeCategoryDivisionCell.swift
//  ESR
//
//  Created by Pratik Patel on 28/08/19.
//

import UIKit
import Foundation

class AgeCategoryDivisionCell: UITableViewCell {

    @IBOutlet var heightConstraintImgViewRightArrow: NSLayoutConstraint!
    @IBOutlet var widthConstraintImgViewRightArrow: NSLayoutConstraint!
    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var imgViewRightArrow: UIImageView!
    @IBOutlet var stackView: UIStackView!
    
    var cellIndexPath: IndexPath!
    
    var divisionObjList = NSArray()
    
    var didTapView: ((NSDictionary) -> ())?
    var didUpdateTableView: ((IndexPath) -> ())?
    
    var isExpanded = false
    
    var record: NSDictionary!
    
    var cellOwner = TableCellOwner()
    
    override func awakeFromNib() {
        super.awakeFromNib()
        lblTitle.font = UIFont.init(name: Font.HELVETICA_MEDIUM, size: Font.Size.commonLblSize)
        lblTitle.textColor = .txtDefaultTxt
    }
    
    func showHideDivisions() {
        isExpanded = !isExpanded
        imgViewRightArrow.image = !isExpanded ? UIImage(named: "arrow_right_green_agecat")! : UIImage(named: "arrow_down_green_agecat")!
        
        heightConstraintImgViewRightArrow.constant = isExpanded ? 13 : 18
        widthConstraintImgViewRightArrow.constant = isExpanded ? 18 : 13
        
        for view in stackView.arrangedSubviews {
            view.isHidden = !isExpanded
        }
        
        didUpdateTableView?(cellIndexPath)
    }
    
    @objc func onTapMainView(_ gesture: UITapGestureRecognizer) {
        showHideDivisions()
    }
    
    @objc func onTapView(_ gesture: UITapGestureRecognizer) {
        if let view = gesture.view {
            didTapView?(divisionObjList[view.tag] as! NSDictionary)
        }
    }
    
    func reloadCell() {
        if let text = record.value(forKey: "title") as? String {
            lblTitle.text = text
        }
        
        for i in 0..<divisionObjList.count {
            if let dicProduct = divisionObjList[i] as? NSDictionary {
                
                if cellOwner.loadMyNibFile(nibName: "AgeCategoryDivisionView") {
                    if let newView = cellOwner.view {
                        if let name = dicProduct.value(forKey: "display_name") as? String {
                            (newView as! AgeCategoryDivisionView).lblTitle.text = name
                        }
                        
                        newView.tag = i
                        let tap = UITapGestureRecognizer(target: self, action: #selector(AgeCategoryDivisionCell.onTapView(_:)))
                        tap.cancelsTouchesInView = false
                        newView.addGestureRecognizer(tap)
                        
                        newView.addConstraint(NSLayoutConstraint(item: newView, attribute: NSLayoutConstraint.Attribute.height, relatedBy: NSLayoutConstraint.Relation.equal, toItem: nil, attribute: NSLayoutConstraint.Attribute.notAnAttribute, multiplier: 1, constant: 40))
                        newView.isHidden = true
                        
                        stackView.addArrangedSubview(newView)
                    }
                }
            }
        }
    }
}

