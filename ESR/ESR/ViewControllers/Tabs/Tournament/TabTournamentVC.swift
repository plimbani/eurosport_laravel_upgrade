//
//  TabTournamentVC.swift
//  ESR
//
//  Created by Pratik Patel on 14/08/18.
//

import UIKit
import MBCircularProgressBar
import SDWebImage

class TabTournamentVC: SuperViewController {

    @IBOutlet var tournamentSelectView: UIView!
    @IBOutlet var lblTournamentDate: UILabel!
    @IBOutlet var lblTournamentName: UILabel!
    
    @IBOutlet var tounamentImgView: UIImageView!
    @IBOutlet var containerViewTop: UIView!
    @IBOutlet var lblSelectedTournamentName: UILabel!
    
    @IBOutlet var daysProgressViewContainer: UIView!
    @IBOutlet var hoursProgressViewContainer: UIView!
    @IBOutlet var minutesProgressViewContainer: UIView!
    @IBOutlet var secondsProgressViewContainer: UIView!
    
    var daysProgressView: MBCircularProgressBarView!
    var hoursProgressView: MBCircularProgressBarView!
    var minutesProgressView: MBCircularProgressBarView!
    var secondsProgressView: MBCircularProgressBarView!
    
    var tournamentList = [Tournament]()
    // var selectedTournament = Tournament()
    var titleList = [String]()
    
    var pickerHandlerView: PickerHandlerView!
    var delegate: MainTabViewControllerDelegate?
    
    var tournamentDetailsView: TournamentDetailsView!
    
    var seconds = 60
    var timer: Timer?
    var isTimerRunning = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    override func viewDidAppear(_ animated: Bool) {
        ApplicationData.setBorder(containerViewTop, Color: .darkGray, thickness: 1.0, type: ViewBorderType.top)
        ApplicationData.setBorder(containerViewTop, Color: .darkGray, thickness: 1.0, type: ViewBorderType.bottom)
        ApplicationData.setBorder(containerViewTop, Color: .darkGray, thickness: 1.0, type: ViewBorderType.right)
        ApplicationData.setBorder(containerViewTop, Color: .darkGray, thickness: 1.0, type: ViewBorderType.left)
        
        if timer == nil {
            runTimer()
        }
    }
    
    override func viewDidDisappear(_ animated: Bool) {
        if timer != nil {
            timer!.invalidate()
            timer = nil
        }
    }
    
    func initialize() {
        self.navigationController?.isNavigationBarHidden = true
        
        daysProgressView = getProgressView(maxValue: 30)
        hoursProgressView = getProgressView(maxValue: 24)
        minutesProgressView = getProgressView(maxValue: 60)
        secondsProgressView = getProgressView(maxValue: 60)
        
        daysProgressViewContainer.addSubview(daysProgressView)
        hoursProgressViewContainer.addSubview(hoursProgressView)
        minutesProgressViewContainer.addSubview(minutesProgressView)
        secondsProgressViewContainer.addSubview(secondsProgressView)
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        let tap = UITapGestureRecognizer(target: self, action: #selector(onSelectTournament(_:)))
        tournamentSelectView.addGestureRecognizer(tap)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        pickerHandlerView = getPickerView()
        pickerHandlerView.delegate = self
        self.view.addSubview(pickerHandlerView)
        
        _ = cellOwner.loadMyNibFile(nibName: "TournamentDetailsView")
        tournamentDetailsView = cellOwner.view as! TournamentDetailsView
        tournamentDetailsView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: DEVICE_HEIGHT)
        tournamentDetailsView!.hide()
        self.view.addSubview(tournamentDetailsView!)
        
        sendGetTournamentsRequest()
    }
    
    func runTimer() {
        timer = Timer.scheduledTimer(timeInterval: 1, target: self, selector: #selector(updateTimer), userInfo: nil, repeats: true)
    }
    
    @objc func updateTimer() {
        convertToCountdownTime()
    }
    
    @objc func onSelectTournament(_ sender : UITapGestureRecognizer) {
        pickerHandlerView.show()
    }
    
    func getProgressView(maxValue: CGFloat) -> MBCircularProgressBarView {
        let progressBarView = MBCircularProgressBarView()
        progressBarView.backgroundColor = .white
        progressBarView.frame = CGRect(x: 0, y: 0, width: 60, height: 60)
        // Progress
        progressBarView.progressColor = UIColor.AppColor()
        progressBarView.progressStrokeColor = .clear
        progressBarView.progressAngle = 100
        progressBarView.progressCapType = 2
        // Line
        progressBarView.progressLineWidth = 6.0
        progressBarView.emptyLineWidth = 2.0
        progressBarView.maxValue = maxValue
        progressBarView.value = 0
        // Fonts
        progressBarView.valueFontName = Font.HELVETICA_BOLD
        progressBarView.valueFontSize = 18
        progressBarView.fontColor = .black
        // Unit
        progressBarView.unitString = NULL_STRING
        return progressBarView
    }
    
    func updateTournamentDetails() {
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            if selectedTournament.logo != NULL_STRING {
                tounamentImgView.sd_setImage(with: URL(string: selectedTournament.logo), placeholderImage:nil)
            }
            
            lblTournamentName.text = selectedTournament.name
            lblSelectedTournamentName.text = selectedTournament.name
            
            var tournamentDate = NULL_STRING
            
            let calendar = Calendar.current
            let monthString = selectedTournament.startDateObj.getMonthName()
            let endDay = calendar.component(.day, from: selectedTournament.endDateObj)
            let startDay = calendar.component(.day, from: selectedTournament.startDateObj)
            let yearString = calendar.component(.year, from: selectedTournament.startDateObj)
            
            tournamentDate = "\(startDay) - \(endDay) \(monthString) \(yearString)"
            lblTournamentDate.text = tournamentDate
            
            convertToCountdownTime()
        }
    }
    
    func convertToCountdownTime() {
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            if let tournamentStartTime = ApplicationData.getFormattedDate(selectedTournament.tournamentStartTime, dateFormat: kDateFormat.format3) {
                
                let calendar = NSCalendar.current
                let unitFlags = Set<Calendar.Component>([.day, .hour, .minute, .second])
                let dateComponents: DateComponents? = calendar.dateComponents(unitFlags, from: Date(),  to: tournamentStartTime)
                
                if let components = dateComponents {
                    if (components.day ?? 0) > 0 {
                        daysProgressView.value = CGFloat(components.day ?? 0)
                    } else {
                        daysProgressView.value = 0
                    }
                    if Int(components.minute ?? 0) > 0 {
                        minutesProgressView.value = CGFloat(components.minute ?? 0)
                    } else {
                        minutesProgressView.value = 0
                    }
                    if Int(components.hour ?? 0) > 0 {
                        hoursProgressView.value = CGFloat(components.hour ?? 0)
                    } else {
                        hoursProgressView.value = 0
                    }
                    if Int(components.second ?? 0) > 0 {
                        secondsProgressView.value = CGFloat(components.second ?? 0)
                    } else {
                        secondsProgressView.value = 0
                    }
                }
            }
        }
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }
    
    @IBAction func onFinalPlacingPressed(_ sender: UIButton) {
        let viewController = Storyboards.Teams.instantiateClubCategoryVC()
        viewController.isFromTournament = true
        // viewController.tournamentId = ApplicationData.selectedTournament.id
        self.navigationController?.pushViewController(viewController, animated: true)
    }
    
    @IBAction func onTeamPressed(_ sender: UIButton) {
        delegate!.mainTabViewControllerSelectTab(TabIndex.tabTeams.rawValue)
    }
    
    @IBAction func onTournamentDetailsPressed(_ sender: UIButton) {
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            tournamentDetailsView.nameString = selectedTournament.firstName + " " + selectedTournament.lastName
            tournamentDetailsView.contactString = selectedTournament.telephone
            tournamentDetailsView.reload()
        }
        
        tournamentDetailsView.show()
    }
    
    func sendGetTournamentsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = userData.id
        }
        
        self.view.showProgressHUD()
        ApiManager().getFavTournaments(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                self.titleList.removeAll()
                
                if let tournamentList = result.value(forKey: "data") as? NSArray {
                    for dicTournament in tournamentList {
                        
                        let tournament = ParseManager.parseFavTournament(dicTournament as! NSDictionary)
                        
                        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
                            if selectedTournament.id == tournament.id {
                                ApplicationData.sharedInstance().saveSelectedTournament(tournament)
                                self.lblSelectedTournamentName.text = tournament.name
                            }
                        } else {
                            if tournament.isDefault == 1 {
                                ApplicationData.sharedInstance().saveSelectedTournament(tournament)
                                
                                if let userData = ApplicationData.sharedInstance().getUserData() {
                                    userData.tournamentId = tournament.id
                                    ApplicationData.sharedInstance().saveUserData(userData)
                                }
                            }
                        }
//                        if ApplicationData.selectedTournament != nil {
//                            if  ApplicationData.selectedTournament!.id == tournament.id {
//                                ApplicationData.selectedTournament = tournament
//                            }
//                        } else {
//
//                        }
                            self.tournamentList.append(tournament)
                    }
                    
                    // Sort array by start date
                    self.tournamentList.sort(by: { (t1, t2) -> Bool in
                        return (t1.startDateObj.timeIntervalSinceNow > t2.startDateObj.timeIntervalSinceNow)
                    })
                    
                    for tournament in self.tournamentList {
                        self.titleList.append(tournament.name)
                    }
                    
                    self.pickerHandlerView.titleList = self.titleList
                    self.pickerHandlerView.reloadPickerView()
                    self.updateTournamentDetails()
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
}

extension TabTournamentVC: PickerHandlerViewDelegate {
    
    func pickerCancelBtnPressed() {}
    
    func pickerDoneBtnPressed(_ title: String) {
        lblSelectedTournamentName.text = title
        ApplicationData.sharedInstance().saveSelectedTournament(self.tournamentList[pickerHandlerView.selectedPickerPosition])
        updateTournamentDetails()
    }
}
