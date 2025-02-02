# Compiler
CC = g++
# Compiler flags
CFLAGS = -Wall
# Linker flags
LDFLAGS = -lusockets -lssl -lcrypto -lz
# Source files
SRCS = main.cpp
# Object files
OBJS = $(SRCS:.cpp=.o)
# Output executable
TARGET = main.out

all: $(TARGET)

# Rule to link the executable
$(TARGET): $(OBJS)
	$(CC) $(CFLAGS) -o $(TARGET) $(OBJS) $(LDFLAGS)

# Rule to compile source files into object files
%.o: %.cpp
	$(CC) $(CFLAGS) -c $< -o $@

# Clean rule to remove generated files
clean:
	rm -f $(OBJS) $(TARGET)
