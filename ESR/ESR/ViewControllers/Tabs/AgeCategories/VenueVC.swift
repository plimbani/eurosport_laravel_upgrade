//
//  VenueVC.swift
//  ESR
//
//  Created by Pratik Patel on 03/09/18.
//

import UIKit

class VenueVC: SuperViewController {

    @IBOutlet var table: UITableView!
    @IBOutlet var btnVenueViewOnMap: UIButton!
    
    var fieldList = NSArray()
    
    var lblPitch: UILabel!
    var lblType: UILabel!
    var lblLocation: UILabel!
    var lblAddress: UILabel!
    
    var dicTeamFixture: TeamFixture!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        titleNavigationBar.lblTitle.text = String.localize(key: "title_venue")
        
        table.tableFooterView = UIView()
        table.separatorColor = UIColor.clear
        
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
        let viewController = Storyboards.Main.instantiateMapVC()
        viewController.strLocation = dicTeamFixture.venueCoordinates
        viewController.strVenueName = dicTeamFixture.venueName
        self.navigationController?.pushViewController(viewController, animated: true)
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
        return UITableView.automaticDimension
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
                        textFieldCell.bottomView.isHidden = false
                        textFieldCell.reloadCell()
                        cell = textFieldCell
                        
                        if dicTeamFixture != nil {
                            if indexPath.row == 0 {
                                lblPitch = textFieldCell.lblTitle
                                
                                if dicTeamFixture.pitchNumber != NULL_STRING {
                                    lblPitch.text = dicTeamFixture.pitchNumber
                                } else {
                                    lblPitch.text = String.localize(key: "string_na")
                                }
                                
                            } else if indexPath.row == 1 {
                                lblType = textFieldCell.lblTitle
                                
                                if dicTeamFixture.pitchType != NULL_STRING {
                                    lblType.text = dicTeamFixture.pitchType.capitalizingFirstLetter()
                                } else {
                                    lblType.text = String.localize(key: "string_na")
                                }
                                
                            } else if indexPath.row == 2 {
                                lblLocation = textFieldCell.lblTitle
                                
                                if dicTeamFixture.venueName != NULL_STRING {
                                    lblLocation.text = dicTeamFixture.venueName
                                } else {
                                    lblLocation.text = String.localize(key: "string_na")
                                }
                                
                                btnVenueViewOnMap.isHidden = (dicTeamFixture.venueCoordinates == NULL_STRING)
                            } else if indexPath.row == 3 {
                                lblAddress = textFieldCell.lblTitle
                                
                                var address = NULL_STRING
                                
                                if dicTeamFixture.venueAddress != NULL_STRING {
                                    address = dicTeamFixture.venueAddress
                                }
                                
                                if dicTeamFixture.venueCity != NULL_STRING {
                                    address += ", " + dicTeamFixture.venueCity
                                }
                                
                                if dicTeamFixture.venueCountry != NULL_STRING {
                                    address += ", " + dicTeamFixture.venueCountry
                                }
                                
                                if dicTeamFixture.venuePostcode != NULL_STRING {
                                    address += ", " + dicTeamFixture.venuePostcode
                                }
                                
                                if address != NULL_STRING {
                                    lblAddress.text = address
                                } else {
                                    lblAddress.text = String.localize(key: "string_na")
                                }
                            }
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

