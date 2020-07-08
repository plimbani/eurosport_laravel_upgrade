//
//  NSLog.swift
//  ESR comm
//
//  Created by Pratik Patel on 08/07/20.
//

import Foundation

public func NSLog(_ format: String, _ args: CVarArg...) {
   let message = String(format: format, arguments:args)
    print(message);
   TFLogv(message, getVaList([]))
}
