//
//  VenueVC.swift
//  ESR
//
//  Created by Pratik Patel on 03/09/18.
//

import UIKit

class VenueVC: SuperViewController {

    @IBOutlet var table: UITableView!
    var fieldList = NSArray()
    
    var lblPitch: UILabel!
    var lblType: UILabel!
    var lblLocation: UILabel!
    var lblAddress: UILabel!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        titleNavigationBar.lblTitle.text = String.localize(key: "title_venue")
        
        table.tableFooterView = UIView()
        table.separatorColor = UIColor.labelSlectionBg
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // Fieldlist
        if let url = Bundle.main.url(forResource: "VenueFieldList", withExtension: "plist") {
            fieldList = NSArray(contentsOf: url)!
        }
    }
    
    @IBAction func viewOnMapBtnPressed(_ sender: UIButton) {
        
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }
}

extension VenueVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension VenueVC : UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return fieldList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return UITableViewAutomaticDimension
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
            let field = fieldList[indexPath.row] as! NSDictionary
        
            if let rawValue = field.value(forKey: "cellType") as? Int {
                var cell:UITableViewCell! = nil
                if let cellType = CellType(rawValue: rawValue) {
                    switch(cellType) {
                    case .TwoLabelCell:
                        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TwoLabelCell)
                        let textFieldCell = cellOwner.cell as! TwoLabelCell
                        textFieldCell.record = field
                        textFieldCell.reloadCell()
                        cell = textFieldCell
                        
                        if indexPath.row == 0 {
                            lblPitch = textFieldCell.lblTitle
                        } else if indexPath.row == 1 {
                            lblType = textFieldCell.lblTitle
                        } else if indexPath.row == 2 {
                            lblLocation = textFieldCell.lblTitle
                        } else if indexPath.row == 3 {
                            lblAddress = textFieldCell.lblTitle
                        }
                        
                        
                    default:
                        print("Default")
                    }
                }
                cell.backgroundColor = .clear
                return cell
            
        }
        return UITableViewCell()
    }
}

